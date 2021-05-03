<?php

use App\Http\Controllers\HomeController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Api\V1\Auth\ResetPasswordController;
use App\Http\Controllers\Web\BioController;
use App\Http\Controllers\Web\BookDetailController;
use App\Http\Controllers\Web\NewsController;
use App\Http\Controllers\Web\UpcomingController;
use App\Http\Controllers\Web\WriteFormController;
use App\Http\Controllers\Web\ContactController;
use App\Http\Controllers\Web\ConditionController;
use App\Http\Controllers\Web\CatalogueController;
use App\Http\Controllers\Web\ManuscriptController;
use App\Http\Controllers\Web\ManusettingController;
use App\Http\Controllers\Web\WriterController;
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
    Route::get('/contact', [ContactController::class, 'index'])->name('contact');
    Route::get('/catalog', [CatalogueController::class, 'index'])->name('catalog');
    Route::get('/book_details', [BookDetailController::class, 'index'])->name('books_details');
	// it's write_form router
    Route::get('/write_form', [WriteFormController::class, 'index'])->name('writeform');
    Route::get('/condition', [ConditionController::class, 'index'])->name('condition');
    Route::get('/manuscript', [ManuscriptController::class, 'index'])->name('manuscript');
    Route::get('/manusetting', [ManusettingController::class, 'index'])->name('manusetting');
    Route::get('/writers', [WriterController::class, 'index'])->name('writers');
    // Password Reset Routes...
    Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.api.update');
    Route::view('/password/reset/success', 'auth.passwords.api.success');

	Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
	    $request->fulfill();

	    return redirect('/');
	})->middleware(['auth', 'signed'])->name('verification.verify');
});
