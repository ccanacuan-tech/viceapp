<?php

namespace App\Http\Controllers;

use App\Models\Planning;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PlanningController extends Controller
{
    public function index(Request $request)
    {
        $query = Planning::with('subject')->where('user_id', Auth::id());

        if ($request->has('search') && $request->input('search') != '') {
            $query->where('title', 'like', '%' . $request->input('search') . '%');
        }

        if ($request->has('status') && $request->input('status') != '') {
            $query->where('status', $request->input('status'));
        }

        $plannings = $query->latest()->paginate(10);
        $subjects = Subject::all();

        return view('plannings.index', compact('plannings', 'subjects'));
    }

    public function review(Request $request)
    {
        $plannings = Planning::with('user', 'subject')
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
            'subject_id' => 'required|exists:subjects,id',
        ]);

        $path = $request->file('file')->store('plannings', 'public');

        Planning::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'file_path' => $path,
            'subject_id' => $request->subject_id,
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
            $planning->load('comments.user', 'subject');
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
        $redirectRoute = 'plannings.review'; // Default redirect for admins

        if ($user->hasRole('docente')) {
            if (!(($currentStatus === 'borrador' && $newStatus === 'revisión') || 
                  ($currentStatus === 'rechazado' && $newStatus === 'revisión'))) {
                abort(403, 'Como docente, solo puedes enviar a revisión un borrador o un documento rechazado.');
            }
            $redirectRoute = 'plannings.index'; // Docentes redirect to their index
        } elseif ($user->hasRole('secretaria') || $user->hasRole('vicerrector')) {
            if ($currentStatus !== 'revisión') {
                abort(403, 'Solo puedes aprobar o rechazar planificaciones que estén en estado de revisión.');
            }
        } else {
            abort(403, 'No tienes permisos para cambiar el estado de esta planificación.');
        }

        $planning->update(['status' => $newStatus]);

        return redirect()->route($redirectRoute)->with('success', 'El estado de la planificación ha sido actualizado correctamente.');
    }

    public function destroy(Planning $planning)
    {
        // Ensure the user is authorized to delete the planning
        if (Auth::id() !== $planning->user_id || $planning->status !== 'borrador') {
            abort(403, 'No tienes permiso para eliminar esta planificación.');
        }

        // Delete the file from storage
        Storage::disk('public')->delete($planning->file_path);

        // Delete the planning from the database
        $planning->delete();

        return redirect()->route('plannings.index')->with('success', 'Planificación eliminada exitosamente.');
    }
}
