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

Route::get('/', 'UsersController@index');

// ユーザ登録
Route::get('signup', 'Auth\RegisterController@showRegistrationForm')->name('signup');
Route::post('signup', 'Auth\RegisterController@register')->name('signup.post');

//ログイン機能追加
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login')->name('login.post');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');

//ログインしていないユーザは、一覧の閲覧のみ可
Route::resource('users', 'UsersController', ['only' => ['show']]);

//ログインしていないユーザは、誰をフォローしているか、誰にフォローされているかを閲覧可能
Route::group(['prefix' => 'users/{id}'], function () {
    Route::get('followings', 'UsersController@followings')->name('followings');
    Route::get('followers', 'UsersController@followers')->name('followers');
    });
    
//RESTfulAPI
Route::resource('rest','RestappController', ['only' => ['index', 'show', 'create', 'store', 'destroy']]);

//ログインしているユーザのみが、名前の変更が可能
Route::group(['middleware' => 'auth'], function () {
    Route::put('users', 'UsersController@rename')->name('rename');
    
    //ログインしているユーザのみが、フォロー、アンフォローが可能
    Route::group(['prefix' => 'users/{id}'], function () {
        Route::post('follow', 'UserFollowController@store')->name('follow');
        Route::delete('unfollow', 'UserFollowController@destroy')->name('unfollow');
    });
    
    //ログインしているユーザのみが、create処理、store処理、destroy処理が可能
    Route::resource('movies', 'MoviesController', ['only' => ['create', 'store', 'destroy']]);
});
