<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PlanningController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\GoogleDriveController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/plannings', [PlanningController::class, 'index'])->name('plannings.index');
    Route::post('/plannings', [PlanningController::class, 'store'])->name('plannings.store');
    Route::get('/plannings/{planning}/download', [PlanningController::class, 'download'])->name('plannings.download');
    Route::get('/plannings/{planning}/view', [PlanningController::class, 'view'])->name('plannings.view');
    Route::patch('/plannings/{planning}/status', [PlanningController::class, 'updateStatus'])->name('plannings.updateStatus');

    Route::post('/plannings/{planning}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');

    Route::middleware('role:secretaria')->group(function () {
        Route::get('/admin/plannings', [PlanningController::class, 'adminIndex'])->name('plannings.adminIndex');
    });

    Route::get('/google-drive/connect', [GoogleDriveController::class, 'connect'])->name('google.connect');
    Route::get('/google-drive/callback', [GoogleDriveController::class, 'callback'])->name('google.callback');
    Route::get('/google-drive/picker', [GoogleDriveController::class, 'picker'])->name('google.picker');
});

require __DIR__.'/auth.php';
