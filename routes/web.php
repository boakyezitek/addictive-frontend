<?php

use App\Http\Controllers\HomeController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Api\V1\Auth\ResetPasswordController;
use App\Http\Controllers\Web\NewsController;
use App\Http\Controllers\Web\UpcomingController;
use Illuminate\Support\Facades\Route;

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
Route::domain(config('localhost:8000'))->group(function () {
	Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/news', [NewsController::class, 'index'])->name('news');
    Route::get('/upcoming', [UpcomingController::class, 'index'])->name('upcoming');
	Route::get('/app/login', [HomeController::class, 'index'])->name('app.login');
	Route::get('/web/redirect', [HomeController::class, 'index'])->name('app.redirect');
    // Password Reset Routes...
    Route::get('/app/password/reset', [ResetPasswordController::class, 'showResetForm'])->name('password.api.reset');
    Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.api.update');
    Route::view('/password/reset/success', 'auth.passwords.api.success');

	Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
	    $request->fulfill();

	    return redirect('/');
	})->middleware(['auth', 'signed'])->name('verification.verify');

    //pages route

});



