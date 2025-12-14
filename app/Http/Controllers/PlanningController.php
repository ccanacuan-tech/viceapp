<?php

namespace App\Http\Controllers;

use App\Models\Planning;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PlanningController extends Controller
{
    public function index(Request $request)
    {
        $query = Planning::where('user_id', Auth::id());

        if ($request->has('search') && $request->input('search') != '') {
            $query->where('title', 'like', '%' . $request->input('search') . '%');
        }

        if ($request->has('status') && $request->input('status') != '') {
            $query->where('status', $request->input('status'));
        }

        $plannings = $query->latest()->paginate(10);

        return view('plannings.index', compact('plannings'));
    }

    public function review(Request $request)
    {
        $plannings = Planning::with('user')
            ->where('status', 'revisión')
            ->latest()
            ->paginate(15);

        return view('plannings.review', compact('plannings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'required|file|max:10240|mimetypes:application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ]);

        $path = $request->file('file')->store('plannings', 'public');

        Planning::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'file_path' => $path,
        ]);

        return redirect()->route('plannings.index')->with('success', 'Planificación subida exitosamente.');
    }

    public function download(Planning $planning)
    {
        $user = Auth::user();
        if ($user->hasRole('secretaria') || $user->hasRole('vicerrector') || $planning->user_id === $user->id) {
            return Storage::disk('public')->download($planning->file_path);
        }
        abort(403, 'Acción no autorizada.');
    }

    public function view(Planning $planning)
    {
        $user = Auth::user();
        if ($user->hasRole('secretaria') || $user->hasRole('vicerrector') || $planning->user_id === $user->id) {
            $planning->load('comments.user');
            return view('plannings.view', compact('planning'));
        }
        abort(403, 'Página no encontrada o sin permisos de acceso.');
    }

    public function updateStatus(Request $request, Planning $planning)
    {
        $request->validate([
            'status' => 'required|in:borrador,revisión,aprobado,rechazado',
        ]);

        $user = Auth::user();
        $currentStatus = $planning->status;
        $newStatus = $request->status;

        if ($user->hasRole('docente')) {
            if (!(($currentStatus === 'borrador' && $newStatus === 'revisión') || 
                  ($currentStatus === 'rechazado' && $newStatus === 'revisión'))) {
                abort(403, 'Como docente, solo puedes enviar a revisión un borrador o un documento rechazado.');
            }
        } elseif ($user->hasRole('secretaria') || $user->hasRole('vicerrector')) {
            if ($currentStatus !== 'revisión') {
                abort(403, 'Solo puedes aprobar o rechazar planificaciones que estén en estado de revisión.');
            }
        } else {
            abort(403, 'No tienes permisos para cambiar el estado de esta planificación.');
        }

        $planning->update(['status' => $newStatus]);

        // Aquí puedes agregar la lógica de notificación que necesites

        return redirect()->route('plannings.review')->with('success', 'El estado de la planificación ha sido actualizado correctamente.');
    }
}
