<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/







Route::get('/', function () {
    return view('tasks');
});

Route::name('tasks')->group(function(){
    Route::get('/tasks/list', [TaskController::class, 'index']);
    Route::put('/tasks/completed/{id}', [TaskController::class, 'completedTask']);
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy']);
    Route::put('/tasks/{id}', [TaskController::class, 'update']);
});

Route::name('users')->group(function(){
    Route::get('/users',function(){return view('users');});
    Route::get('/users/list',[UserController::class,'index']);
    Route::post('/users',[UserController::class,'store']);
    Route::delete('/users/{id}',[UserController::class,'destroy']);
    Route::put('/users/{id}',[UserController::class,'update']);
});