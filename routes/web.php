<?php

use App\Http\Controllers\CreateContactController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;

use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Contacts/Index'); // ou 'Home' se quiser
});


Route::resource('contacts', ContactController::class)->only([
    'index', 'store', 'update', 'destroy'
]);