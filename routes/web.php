<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/reportes', 'pages.reports.index');
Route::view('/reportes/{report}/report', 'pages.reports.index');
