<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

use App\Http\Controllers\Api\V1\BookController;
use App\Http\Controllers\Api\V1\HomeController;
use App\Http\Controllers\Api\V1\SyncController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\BonusController;
use App\Http\Controllers\Api\V1\AuthorController;
use App\Http\Controllers\Api\V1\ChapterController;
use App\Http\Controllers\Api\V1\BookmarkController;
use App\Http\Controllers\Api\V1\AudioBookController;
use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\EdisourceController;
use App\Http\Controllers\Api\V1\ParameterController;
use App\Http\Controllers\Api\V1\RevenueCatController;
use App\Http\Controllers\Api\V1\InstallationController;
use App\Http\Controllers\Api\V1\Auth\VerificationController;
use App\Http\Controllers\Api\V1\Auth\ResetPasswordController;
use App\Http\Controllers\Api\V1\Auth\ForgotPasswordController;

Route::group(['prefix' => 'v1'], function () {
    Route::post('installations', [InstallationController::class, 'store']);
    Route::put('installations/{uuid}', [InstallationController::class, 'update'])->name('api.installation_update');
    Route::get('email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verificationapi.verify');
    Route::middleware(['revenue_cat'])->group(function () {
        Route::prefix('revenue_cat')->group(function () {
            Route::post('webhook', [RevenueCatController::class, 'webhook'])->name('revenue_cat.webhook');
        });
    });
    Route::middleware(['edisource'])->group(function () {
        Route::prefix('edisource')->group(function () {
            Route::post('webhook', [EdisourceController::class, 'webhook'])->name('edisource.webhook');
        });
    });

    // Route for website
    Route::middleware(['addictives'])->prefix('web')->group(function () {
        Route::prefix('books')->name('books.')->group(function () {
            Route::get('/all', [BookController::class, 'index'])->name('index');
            Route::get('/{book}', [BookController::class, 'show'])->name('show');
        });

        Route::prefix('authors')->name('authors.')->group(function() {
            Route::get('/all', [AuthorController::class, 'all'])->name('all');
            Route::get('/{author}', [AuthorController::class, 'index'])->name('show');
        });
    });

    // api routes with installation header
    Route::group(['middleware' => 'installation'], function () {
        Route::prefix('auth')->group(function () {
            Route::post('email', [AuthController::class, 'emailVerify'])->name('email');
            Route::post('login', [AuthController::class, 'login'])->name('login');
            Route::post('login/{provider}', [AuthController::class, 'socialLogin'])
                ->where('provider', '(?:facebook|google|apple)')
                ->name('login.social');
            Route::post('register', [UserController::class, 'store'])->name('register');
            Route::post('refresh-token', [AuthController::class, 'refreshToken'])->name('refreshToken');
            Route::post('forgotten-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('forgottenPassword');
            Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
        });

        Route::apiResource('users', UserController::class)->except('store');
        Route::get('login_screen_pictures', [HomeController::class, 'loginScreenPicture'])->name('login_screen_pictures');
        Route::get('home_subscription_sections', [HomeController::class, 'subscriptionSection'])->name('home_subscription_sections');
        Route::get('home_subscription_offer', [HomeController::class, 'subcriptionOffer'])->name('home_subscription_offer');
        //Route::apiResource('chapters', 'ChapterController');
        Route::get('chapters/wave', [ChapterController::class, 'wave']);

        Route::get('email/resend', [VerificationController::class, 'resend'])->middleware('auth:api')->name('verificationapi.resend');
        Route::middleware(['auth:api'])->group(function (){
            Route::prefix('me')->group(function () {
                Route::get('/', [UserController::class, 'show'])->name('show');
                Route::patch('/update', [UserController::class, 'update'])->name('update');
            });
        });

        Route::prefix('parameters')->name('parameters.')->group(function() {
            Route::get('/all', [ParameterController::class, 'index'])->name('index');
        });

        Route::middleware(['auth:api', 'verified'])->group(function () {
            Route::prefix('me')->group(function () {
                Route::get('/offerings', [RevenueCatController::class, 'index'])->name('offerings');
                Route::post('/rating/true', [UserController::class, 'rating'])->name('rating.true');
                Route::post('/rating/false', [UserController::class, 'rating'])->name('rating.false');
                Route::post('/trial/true', [UserController::class, 'freeSubscription'])->name('trial.true');
                Route::post('/trial/false', [UserController::class, 'freeSubscriptionDeclined'])->name('trial.false');
                Route::patch('/update/password', [UserController::class, 'updatePassword'])->name('update.password');
                Route::delete('/delete', [UserController::class, 'destroy'])->name('destroy');
                Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
                Route::get('/library', [UserController::class, 'libraryaudioBooks'])->name('library');
                Route::get('/purchases', [UserController::class, 'ownedAudioBooks'])->name('purchases');
                Route::get('/credits', [UserController::class, 'credits'])->name('credits');
                Route::get('/bookmarks', [UserController::class, 'ownedAudioBooksBookmarked'])->name('audio_books_bookmarked');
                Route::get('/bookmarks/all',[UserController::class, 'ownedBookmarks'])->name('owned_bookmarks');
                Route::get('/bookmarks/{audio_book}', [UserController::class, 'bookmarksFromOwnedAudioBook'])->name('bookmarks');
            });
            
            Route::prefix('bookmark')->name('bookmark.')->group(function() {
                Route::delete('/{bookmark}', [BookmarkController::class, 'delete'])->name('delete');
                Route::patch('/update/{bookmark}', [BookmarkController::class, 'update'])->name('update');
                Route::post('/{chapter}', [BookmarkController::class, 'store'])->name('store');
            });


            Route::prefix('home')->name('home.')->group(function() {
                Route::get('/', [HomeController::class, 'index'])->name('home');
            });

            Route::prefix('chapters')->name('chapters.')->group(function() {
                Route::get('/{chapter}/download', [ChapterController::class, 'download'])->name('download');
                Route::post('/{chapter}/progress', [ChapterController::class, 'progress'])->name('progress');
            });

            Route::prefix('audio_books')->name('audio_books.')->group(function() {
                Route::get('/all', [AudioBookController::class, 'index'])->name('index');
                Route::get('/search', [AudioBookController::class, 'search'])->name('search');
                Route::get('/{audio_book}', [AudioBookController::class, 'show'])->name('show');
                Route::patch('/mask/{audio_book}', [AudioBookController::class, 'mask'])->name('mask');
                Route::patch('/unmask/{audio_book}', [AudioBookController::class, 'unmask'])->name('unmask');
                Route::patch('/markAsRead/{audio_book}', [AudioBookController::class, 'markAsRead'])->name('markAsRead');
                Route::patch('/markAsUnread/{audio_book}', [AudioBookController::class, 'markAsUnread'])->name('markAsUnread');
                Route::post('/purchase/{audio_book}', [AudioBookController::class, 'purchase'])->name('purchase');
                Route::get('/{audio_book}/extract', [AudioBookController::class, 'extract'])->name('extract');
            });

            Route::prefix('authors')->name('authors.')->group(function() {
                Route::get('/{author}', [AuthorController::class, 'show'])->name('index');
            });

            Route::prefix('bonuses')->name('bonuses.')->group(function() {
                Route::get('/all', [BonusController::class, 'index'])->name('index');
                Route::get('/{bonus}', [BonusController::class, 'show'])->name('show');
                Route::get('/{bonus}/audio', [BonusController::class, 'audio'])->name('audio');
                Route::get('/{bonus}/video', [BonusController::class, 'video'])->name('video');
            });

            Route::prefix('sync')->name('sync.')->group(function() {
                Route::prefix('bookmark')->name('bookmarks.')->group(function() {
                    Route::post('/delete', [SyncController::class, 'deleteBookmarks'])->name('delete');
                    Route::post('/create', [SyncController::class, 'createBookmarks'])->name('create');
                    Route::post('/update', [SyncController::class, 'updateBookmarks'])->name('update');
                    Route::post('/get', [SyncController::class, 'getBookmarks'])->name('get');

                });
            });
        });

    });
});
