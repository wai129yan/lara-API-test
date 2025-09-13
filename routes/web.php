<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;

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

// Dashboard route - redirect to students index
Route::get('/', function () {
    return redirect()->route('students.index');
});

// Student management routes
Route::get('/students', [StudentController::class, 'indexView'])->name('students.index');
Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
Route::post('/students', [StudentController::class, 'store'])->name('students.store');
Route::get('/students/{student}', [StudentController::class, 'show'])->name('students.show');
Route::get('/students/{student}/edit', [StudentController::class, 'edit'])->name('students.edit');
Route::put('/students/{student}', [StudentController::class, 'update'])->name('students.update');
Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');

// Dashboard route
Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
Route::get('/students/dashboard', [StudentController::class, 'dashboard'])->name('students.dashboard');

// API routes for AJAX requests (keeping existing API functionality)
Route::prefix('api')->group(function () {
    Route::get('/students', [StudentController::class, 'index'])->name('api.students.index');
    Route::post('/students', [StudentController::class, 'store'])->name('api.students.store');
    Route::get('/students/{student}', [StudentController::class, 'show'])->name('api.students.show');
    Route::put('/students/{student}', [StudentController::class, 'update'])->name('api.students.update');
    Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('api.students.destroy');
});