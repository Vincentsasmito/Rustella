<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;


Route::get('/get/users', [HomeController::class, 'getAllUser']);
