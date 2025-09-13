<?php

use App\Http\Controllers\StudentController;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// Route::get('/student', function () {
//     return 'I am from  API Student';
// });

Route::get('/student',[StudentController::class,'index']);

Route::get('/student/{id}',[StudentController::class,'show']);

Route::post('/student',[StudentController::class,'store']);

Route::put('/student/edit/{id}',[StudentController::class,'edit']);

Route::delete('/student/{id}',[StudentController::class,'destroy']);