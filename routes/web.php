<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BookingController::class, 'index'])->name('home');
Route::get('/boeken/{service}', [BookingController::class, 'show'])->name('booking.show');
Route::post('/boeken/{service}', [BookingController::class, 'store'])->name('booking.store');
Route::get('/afspraak/{appointment}/bevestiging', [BookingController::class, 'confirmation'])
    ->middleware('signed')
    ->name('booking.confirmation');

// Let op: het beheer is voor deze demo niet afgeschermd. Zie de README.
Route::get('/beheer', [AdminController::class, 'index'])->name('admin.index');
Route::delete('/beheer/afspraken/{appointment}', [AdminController::class, 'destroy'])->name('admin.destroy');
