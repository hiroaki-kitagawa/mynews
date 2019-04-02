<?php

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

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function() {
    Route::get('news/create', 'Admin\NewsController@add');
    Route::post('news/create', 'Admin\NewsController@create');
    // Route::post('news/create', 'Admin\NewsController@upload');
    Route::post('news/edit', 'Admin\NewsController@upload');
    Route::get('/', 'NewsController@upload');
    Route::get('news/edit', 'Admin\NewsController@edit');
    Route::post('news/edit', 'Admin\NewsController@update');
    Route::get('news/delete', 'Admin\NewsController@delete');

    
    // 課題18 プロフィール情報を表示
    Route::get('profile', 'NewsController@profile');
    // 課題4
    Route::get('profile/create', 'Admin\ProfileController@add');
    Route::get('profile/edit', 'Admin\ProfileController@edit');
    // 課題13
    Route::post('profile/create', 'Admin\ProfileController@create');
    Route::post('profile/edit', 'Admin\ProfileController@update');
});

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');

Route::get('/', 'NewsController@index');

