<?php

use App\Http\Controllers\DomainController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('domains.index')
        : view('landing');
});

Route::middleware('auth')->group(function () {
    Route::get(
        '/profile',
        [ProfileController::class, 'edit']
    )->name('profile.edit');

    Route::patch(
        '/profile',
        [ProfileController::class, 'update']
    )->name('profile.update');

    Route::delete(
        '/profile',
        [ProfileController::class, 'destroy']
    )->name('profile.destroy');

    Route::resource('domains', DomainController::class);
});

require __DIR__.'/auth.php';
