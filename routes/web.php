<?php

use App\Http\Controllers\ArticleController;
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

Route::get('/', function () {
    return view('welcome');
});


    // Fetch Articles
    Route::get('/fetch-articles', [ArticleController::class, 'fetchArticles'])->name('web.articles.fetchArticles');



Route::middleware(['auth'])->group(function () {
    // Display all articles (with pagination)
    Route::get('/articles', [ArticleController::class, 'index'])->name('web.articles.index');

    // Search articles (can accept query parameters)
    Route::get('/articles/search', [ArticleController::class, 'search'])->name('web.articles.search');

    // Display a single article
    Route::get('/articles/{id}', [ArticleController::class, 'show'])->name('web.articles.show');




});
