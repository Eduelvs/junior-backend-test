<?php

use App\Http\Controllers\CreateContactController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('contacts', ContactController::class)->only([
    'index', 'store', 'update', 'destroy'
]);