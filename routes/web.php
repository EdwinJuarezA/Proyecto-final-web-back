<?php

use App\Http\Controllers\NoteController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});



//Route::get('/notes',[NoteController::class, 'index']);

// Route::get('/users',[UserController::class,'index']);
// Route::get('/users/{id}',[UserController::class,'show']);
// Route::delete('/users/{id}',[UserController::class,'destroy']);
// Route::post('/users',[UserController::class,'store']);
// Route::put('/users/{id}',[UserController::class,'update']);