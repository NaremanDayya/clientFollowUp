<?php

use App\Http\Controllers\ProfileController;
use App\Livewire\ChatPanel;
use App\Livewire\ClientProfile;
use App\Livewire\ClientTable;
use App\Livewire\Dashboard;
use App\Livewire\SettingsPage;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/clients', ClientTable::class)->name('clients.index');
    Route::get('/clients/{client}', ClientProfile::class)->name('clients.show');
    Route::get('/chats', ChatPanel::class)->name('chats.index');
    Route::get('/chats/{chat}', ChatPanel::class)->name('chats.show');

    // Admin-only
    Route::get('/settings', SettingsPage::class)->name('settings')->middleware('role:admin');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
