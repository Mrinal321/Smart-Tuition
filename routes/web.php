<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SslCommerzPaymentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\TeacherProfileController;
use App\Http\Controllers\RatingController;
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

Route::post('/teachers/{teacher}/rate', [RatingController::class, 'rateTeacher'])->name('teachers.rate')->middleware(['auth', 'verified']);

// Course routes
Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');
Route::get('/courses/{teacher_id}/create', [CourseController::class, 'create'])->name('courses.create');
Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
Route::get('courses/{id}/edit', [CourseController::class, 'edit'])->name('courses.edit');
Route::put('courses/{course}/update', [CourseController::class, 'update'])->name('courses.update');

//events routes
// Route::resource('events', EventController::class);
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/create/{id}', [EventController::class, 'create'])->name('events.create');
Route::post('/events/store', [EventController::class, 'store'])->name('events.store');

// SSLCOMMERZ Start
Route::get('/example1', [SslCommerzPaymentController::class, 'exampleEasyCheckout'])->name('sslcommerz.payment');;
Route::get('/example2', [SslCommerzPaymentController::class, 'exampleHostedCheckout']);

Route::post('/pay', [SslCommerzPaymentController::class, 'index']);
Route::post('/pay-via-ajax', [SslCommerzPaymentController::class, 'payViaAjax']);

Route::post('/success', [SslCommerzPaymentController::class, 'success']);
Route::post('/fail', [SslCommerzPaymentController::class, 'fail']);
Route::post('/cancel', [SslCommerzPaymentController::class, 'cancel']);

Route::post('/ipn', [SslCommerzPaymentController::class, 'ipn']);
//SSLCOMMERZ END

require __DIR__.'/auth.php';

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
