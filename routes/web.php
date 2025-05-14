<?php

use Illuminate\Support\Facades\Route;

Route::get('/web-test', function () {
    return 'Web routes are working!';
});


Route::get('/', function () {
    return view('welcome');
});
