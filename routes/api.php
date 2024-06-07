<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\UbicationController;
use App\Http\Controllers\UserController;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');*/

Route::middleware('auth:sanctum')->group(function(){
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::apiResource('/users',UserController::class)->names('users');
    Route::apiResource('/notes',NoteController::class)->names('notes');
    Route::apiResource('/images',ImageController::class)->names('images');
    Route::apiResource('/categories',CategoryController::class)->names('categories');
    Route::apiResource('/ubications',UbicationController::class)->names('ubications');
    Route::post('/notes/{id}',[NoteController::class,'muchos']);
    Route::get('/notesIncendios', [NoteController::class, 'getIncendiosNotes']);
    Route::get('/notesDerrumbes', [NoteController::class, 'getDerrumbesNotes']);
    Route::get('/notesInundaciones', [NoteController::class, 'getInundacionesNotes']);
    Route::get('/mynotes', [NoteController::class, 'myNotes']);
});
/*
//Rutas para gestionar usuarios
Route::get('/users',[UserController::class,'index']);
Route::get('/users/{id}',[UserController::class,'show']);
Route::delete('/users/{id}',[UserController::class,'destroy']);
Route::post('/users',[UserController::class,'store']);
Route::put('/users/{id}',[UserController::class,'update']);

//Rutas para gestionar notas
Route::get('/notes',[NoteController::class,'index']);
Route::get('/notes/{id}',[NoteController::class,'show']);
Route::delete('/notes/{id}',[NoteController::class,'destroy']);
Route::post('/notes',[NoteController::class,'store']);
Route::put('/notes/{id}',[NoteController::class,'update']);

//Rutas para gestionar categorias
Route::get('/categories',[CategoryController::class,'index']);
Route::get('/categories/{id}',[CategoryController::class,'show']);
Route::delete('/categories/{id}',[CategoryController::class,'destroy']);
Route::post('/categories',[CategoryController::class,'store']);
Route::put('/categories/{id}',[CategoryController::class,'update']);

//Rutas para gestionar imagenes
Route::get('/images',[ImageController::class,'index']);
Route::get('/images/{id}',[ImageController::class,'show']);
Route::delete('/images/{id}',[ImageController::class,'destroy']);
Route::post('/images',[ImageController::class,'store']);
Route::put('/images/{id}',[ImageController::class,'update']);
*/
//ruta para crear un token 
Route::post('/login',[AuthController::class,'login']);