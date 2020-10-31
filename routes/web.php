<?php

use App\Http\Middleware\authen;
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

Route::get('/admin', 'AdminController@loginAdmin')->name('login');
Route::post('/admin', 'AdminController@authenticate')->name('authenticate');
Route::get('/logout', 'AdminController@logout')->name('logout');

Route::prefix('tintuconline')->group(function () {
    Route::get('/home', [
        'as' => 'home',
        'uses' => 'HomeController@index'
    ]);
    Route::get('/home/news{id}', [
        'as' => 'home.news',
        'uses' => 'NewsController@index'
    ]);
    Route::get('/home/search', [
        'as' => 'home.search',
        'uses' => 'NewsController@search'
    ]);
    Route::get('/home/{categoryCode}', [
        'as' => 'home.newscategory',
        'uses' => 'CategoryController@index'
    ]);
});

Route::prefix('admin')->group(function () {
    Route::get('/home', [
        'as' => 'admin.home',
        'uses' => 'AdminController@index'
    ])->middleware('auth');
});
