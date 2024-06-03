<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\MangaController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::group(['prefix' => 'auth'], function() {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::post('/refresh-token', [AuthController::class, 'refreshToken']);
});

Route::middleware('auth:sanctum')->group(function () {

    Route::get('user', [UserController::class,'getUser']);
    Route::post('user/update', [UserController::class,'update']);
    Route::post('user/change-password', [UserController::class,'changePassword']);

    Route::get('manga', [MangaController::class, 'index']);
    Route::get('manga/popular', [MangaController::class, 'getPopularComic']);
    Route::get('manga/{id}', [MangaController::class, 'showById']);
    Route::get('manga/title/{name}', [MangaController::class, 'showByName']);
    Route::get('manga/{id}/chapter', [MangaController::class, 'getListChapters']);
    Route::get('chapter/{id}', [MangaController::class, 'getChapterImage']);
});
