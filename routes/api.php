<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ArtistController;
use App\Http\Controllers\AlbumController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MultimediaController;

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

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [UserController::class, 'index']);

    Route::prefix('artists')->controller(ArtistController::class)->group(function () {
        Route::post('/store', 'store');
        Route::post('/{artist}/update', 'update');
        Route::post('/store/spotify/albums', 'store_spotify_albums');
        Route::post('/store/youtube/videos', 'store_youtube_videos');
        Route::post('/store/member', 'store_member');
    });
});

Route::prefix('artists')->controller(ArtistController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/by-tags', 'index_by_tags');
    Route::get('/tags', 'all_tags');
    Route::get('/{artist}', 'show');
    Route::get('/{id}/youtube', 'youtube');
    Route::get('/{id}/show-image', 'show_image');
});

Route::controller(MultimediaController::class)->group(function () {
    Route::get('/multimedia', 'index');
    Route::get('/multimedia/{id}', 'show');
});