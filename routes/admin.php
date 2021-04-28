<?php

use App\Http\Controllers\Web\Admin\ChapterController;


/*
|--------------------------------------------------------------------------
| Web Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix' => 'admin', 'namespace' => 'Web\Admin'], function ($router) {
    Route::get('audiobook/{audio_book}/chapters', [ChapterController::class, 'show'])->name('audiobook.show');
    Route::post('chapters/upload', [ChapterController::class, 'upload'])->name('chapters.upload');
});
