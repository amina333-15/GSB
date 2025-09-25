<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () { return view('home');});
Route::get('/connecter', function () { return view('login');});
