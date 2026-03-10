<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('main.welcome');
});

Route::get('/menu', function () {
    return view('main.menu');
});

Route::get('/main', function () {
    return view('main.main');
});

Route::get('/orders', function () {
    return view('main.orders');
});

Route::get('/about', function () {
    return view('main.about');
});
