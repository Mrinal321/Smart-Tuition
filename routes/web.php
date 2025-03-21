<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\TeacherProfileController;
use Illuminate\Support\Facades\Auth;
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

Auth::routes(['verify' => true]);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// TeacherController
Route::get('/', [TeacherController::class, 'index'])->name('teacher.index');
Route::get("create", [TeacherProfileController::class,"create"])->name("teacher.create")->middleware(['auth', 'verified']);
Route::post('/create', [TeacherProfileController::class, 'store'])->name('teacher.store');
Route::get('/post', [TeacherController::class, 'post'])->name('post');


// TeacherProfileController
Route::get('/teacher/{id}/profile', [TeacherProfileController::class, 'profile'])->name('teacher.profile');
// Route::post('/teachers/{teacher}/comments', [::class, 'store'])->name('comments.store');
Route::get('teachers/{id}/edit', [TeacherProfileController::class, 'edit'])->name('teacher.edit');
Route::put('teacher/{id}/update', [TeacherProfileController::class, 'update'])->name('teacher.update');


require __DIR__.'/auth.php';

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
