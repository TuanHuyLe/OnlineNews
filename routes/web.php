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

Route::get('/login', 'Admin\AdminController@loginAdmin')->name('login');
Route::post('/admin', 'Admin\AdminController@authenticate')->name('authenticate');
Route::get('/logout', 'Admin\AdminController@logout')->name('logout');

Route::prefix('tintuconline')->namespace('Web')->group(function () {
    Route::get('/home', [
        'as' => 'home',
        'uses' => 'HomeController@index'
    ]);
    Route::get('/{category}', [
        'as' => 'category',
        'uses' => 'HomeController@index'
    ]);
    Route::get('/{news}', [
        'as' => 'news',
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

Route::prefix('admin')->namespace('Admin')->group(function () {
    Route::get('/home', [
        'as' => 'admin.home',
        'uses' => 'AdminController@index'
    ])->middleware('auth');

    Route::get('/categories', [
       'as' => 'categories.index',
       'uses' => 'AdminCategoryController@index',
        'middleware' => 'can:view_category'
    ]);

    Route::get('/news', [
       'as' => 'news.index',
       'uses' => 'AdminNewController@index',
        'middleware' => 'can:view_new'
    ]);

    Route::get('/roles', [
       'as' => 'roles.index',
       'uses' => 'AdminRoleController@index',
        'middleware' => 'can:view_role'
    ]);

    Route::get('/users', [
       'as' => 'users.index',
       'uses' => 'AdminUserController@index',
        'middleware' => 'can:view_member'
    ]);
});
