<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PlanningController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\GoogleDriveController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SubjectController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rutas de Planificaciones
    Route::resource('plannings', PlanningController::class)->except(['create', 'show', 'edit', 'update']);
    Route::get('/plannings/{planning}/download', [PlanningController::class, 'download'])->name('plannings.download');
    Route::get('/plannings/{planning}/view', [PlanningController::class, 'view'])->name('plannings.view');
    Route::patch('/plannings/{planning}/status', [PlanningController::class, 'updateStatus'])->name('plannings.updateStatus');

    // Ruta exclusiva para Revisión (Protegida por Rol)
    Route::get('/plannings/review', [PlanningController::class, 'review'])
        ->middleware('role:secretaria,vicerrector')
        ->name('plannings.review');

    // Rutas de Comentarios
    Route::post('/plannings/{planning}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // Rutas de Notificaciones y Google Drive
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::get('/google-drive/connect', [GoogleDriveController::class, 'connect'])->name('google.connect');
    Route::get('/google-drive/callback', [GoogleDriveController::class, 'callback'])->name('google.callback');
    Route::get('/google-drive/picker', [GoogleDriveController::class, 'picker'])->name('google.picker');
    
    // Rutas para las nuevas secciones
    Route::resource('teachers', TeacherController::class);
    Route::resource('reports', ReportController::class)->only(['index']);

    // Rutas para Áreas Académicas (solo Vicerrector)
    Route::middleware('role:vicerrector')->group(function () {
        Route::resource('subjects', SubjectController::class);
    });
});

require __DIR__.'/auth.php';
