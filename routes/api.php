<?php

use Illuminate\Http\Request;

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
Route::group(['middleware' => 'cors'], function(){
    Route::post('/login', 'Api\Access\AuthController@login')->name('auth.login');

    Route::group(['middleware' => 'jwt.auth'], function () {
        // Access
        Route::get('/logout', 'Api\Access\AuthController@logout')->name('auth.logout');
        Route::get('/verify-user', 'Api\Access\AuthController@verifyUser')->name('auth.verify_user');
    });

    Route::group(['middleware' => 'jwt.auth', 'prefix' => 'v1'], function () {
        // Books
        Route::patch('/books/{id}', 'Api\V1\Book\BookController@restore')->name('books.restore');
        Route::resource('books', 'Api\V1\Book\BookController', ['except' => ['create', 'edit']]);

        // Authors
        Route::patch('/authors/{id}', 'Api\V1\Author\AuthorController@restore')->name('authors.restore');
        Route::resource('authors', 'Api\V1\Author\AuthorController', ['except' => ['create', 'edit']]);

        // Genres
        Route::patch('/genres/{id}', 'Api\V1\Genre\GenreController@restore')->name('genres.restore');
        Route::resource('genres', 'Api\V1\Genre\GenreController', ['except' => ['create', 'edit']]);

        // Publishers
        Route::patch('/publishers/{id}', 'Api\V1\Publisher\PublisherController@restore')->name('publishers.restore');
        Route::resource('publishers', 'Api\V1\Publisher\PublisherController', ['except' => ['create', 'edit']]);
    });
});