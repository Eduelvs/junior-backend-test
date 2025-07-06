<?php

use App\Http\Controllers\CreateContactController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;

use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home'); // ou 'Home' se quiser
});


Route::get('/contacts', [ContactController::class, 'index'])->name('contacts.index');
Route::post('/contacts', [ContactController::class, 'store'])->name('contacts.store');
Route::put('/contacts/{contact}', [ContactController::class, 'update'])->name('contacts.update');
Route::delete('/contacts/{contact}', [ContactController::class, 'destroy'])->name('contacts.destroy');

