<?php

use App\Http\Controllers\Scraper\ScrapController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('scrap')->group(function(){
    Route::get('get-mangas', [ScrapController::class,'getListManga'])->name('get.list.manga');
    Route::get('get-manga-detail', [ScrapController::class,'getMangaDetail'])->name('get.manga.detail');
    Route::get('api/get-mangas', [ScrapController::class,'apiGetListManga'])->name('api.get.list.manga');
    Route::get('api/get-manga-detail', [ScrapController::class,'apiGetMangaDetail'])->name('api.get.manga.detail');
});
