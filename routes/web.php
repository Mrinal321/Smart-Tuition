<?php

use App\Http\Controllers\TeacherController;
use App\Http\Controllers\TeacherProfileController;
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

// TeacherController
Route::get('/', [TeacherController::class, 'index'])->name('teacher.index');
Route::get("create", [TeacherProfileController::class,"create"])->name("teacher.create");
Route::post('/create', [TeacherProfileController::class, 'store'])->name('teacher.store');
Route::get('/post', [TeacherController::class, 'post'])->name('post');

// TeacherProfileController
Route::get('/teacher/{id}/profile', [TeacherProfileController::class, 'profile'])->name('teacher.profile');
// Route::post('/teachers/{teacher}/comments', [CommentController::class, 'store'])->name('comments.store');
Route::get('teachers/{id}/edit', [TeacherProfileController::class, 'edit'])->name('teacher.edit');
Route::put('teacher/{id}/update', [TeacherProfileController::class, 'update'])->name('teacher.update');
