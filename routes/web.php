<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WorkspaceController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ExportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return redirect()->route('workspaces.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Workspaces
    Route::resource('workspaces', WorkspaceController::class)->except(['create', 'edit', 'update']);

    // Documents
    Route::post('/workspaces/{workspace}/documents', [DocumentController::class, 'store'])->name('documents.store');
    Route::delete('/workspaces/{workspace}/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');

    // Chat
    Route::post('/workspaces/{workspace}/chat', [ChatController::class, 'store'])->name('chat.store');

    // Export
    Route::get('/messages/{message}/export', [ExportController::class, 'exportMessage'])->name('messages.export');
});

require __DIR__.'/auth.php';
