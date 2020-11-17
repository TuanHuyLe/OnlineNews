<?php

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


/**
 * Define api admin
 * api/v1/[entity]
 * CreatedBy: LTQUAN (04/11/2020)
 */
Route::prefix('v1')->namespace('Api\Admin')->group(function () {
    Route::prefix('categories')->group(function () {

        Route::get('/', [
            'as' => 'categories.read',
            'uses' => 'CategoryApi@index',
            'middleware' => 'can:view_category'
        ]);

        Route::get('/list', [
            'as' => 'categories.list',
            'uses' => 'CategoryApi@list',
            'middleware' => 'can:view_category'
        ]);

        Route::get('/{id}', [
            'as' => 'categories.readbyid',
            'uses' => 'CategoryApi@show',
            'middleware' => 'can:view_category'
        ]);

        Route::post('/', [
            'as' => 'categories.create',
            'uses' => 'CategoryApi@store',
            'middleware' => 'can:add_category'
        ]);

        Route::put('/', [
            'as' => 'categories.update',
            'uses' => 'CategoryApi@update',
            'middleware' => 'can:edit_category'
        ]);

        Route::delete('/', [
            'as' => 'categories.delete',
            'uses' => 'CategoryApi@destroy',
            'middleware' => 'can:delete_category'
        ]);
    });

    Route::prefix('news')->group(function () {

        Route::get('/', [
            'as' => 'news.read',
            'uses' => 'NewsApi@index',
            'middleware' => 'can:view_new'
        ]);

        Route::get('/{id}', [
            'as' => 'news.readbyid',
            'uses' => 'NewsApi@show',
            'middleware' => 'can:view_new'
        ]);

        Route::post('/', [
            'as' => 'news.create',
            'uses' => 'NewsApi@store',
            'middleware' => 'can:add_new'
        ]);

        Route::put('/', [
            'as' => 'news.update',
            'uses' => 'NewsApi@update',
            'middleware' => 'can:edit_new'
        ]);

        Route::delete('/', [
            'as' => 'news.delete',
            'uses' => 'NewsApi@destroy',
            'middleware' => 'can:delete_new'
        ]);
    });

    Route::prefix('roles')->group(function () {

        Route::get('/', [
            'as' => 'roles.read',
            'uses' => 'RoleApi@index',
            'middleware' => 'can:view_role'
        ]);

        Route::get('/list', [
            'as' => 'roles.list',
            'uses' => 'RoleApi@list',
            'middleware' => 'can:view_role'
        ]);

        Route::get('/{id}', [
            'as' => 'roles.readbyid',
            'uses' => 'RoleApi@show',
            'middleware' => 'can:view_role'
        ]);

        Route::post('/', [
            'as' => 'roles.create',
            'uses' => 'RoleApi@store',
            'middleware' => 'can:add_role'
        ]);

        Route::put('/', [
            'as' => 'roles.update',
            'uses' => 'RoleApi@update',
            'middleware' => 'can:edit_role'
        ]);

        Route::delete('/', [
            'as' => 'roles.delete',
            'uses' => 'UserApi@destroy',
            'middleware' => 'can:delete_role'
        ]);
    });

    Route::prefix('users')->group(function () {

        Route::get('/', [
            'as' => 'users.read',
            'uses' => 'UserApi@index',
            'middleware' => 'can:view_member'
        ]);

        Route::get('/{id}', [
            'as' => 'users.readbyid',
            'uses' => 'UserApi@show',
            'middleware' => 'can:view_member'
        ]);

        Route::post('/', [
            'as' => 'users.create',
            'uses' => 'UserApi@store',
            'middleware' => 'can:add_member'
        ]);

        Route::put('/', [
            'as' => 'users.update',
            'uses' => 'UserApi@update',
            'middleware' => 'can:edit_member'
        ]);

        Route::delete('/', [
            'as' => 'users.delete',
            'uses' => 'UserApi@destroy',
            'middleware' => 'can:delete_member'
        ]);
    });

    Route::prefix('permissions')->group(function () {

        Route::get('/', [
            'as' => 'permissions.read',
            'uses' => 'PermissionApi@index'
        ]);

        Route::get('/list', [
            'as' => 'permissions.list',
            'uses' => 'PermissionApi@list'
        ]);
    });
});

/**
 * Define api web
 * api/v1/[entity]
 * CreatedBy: LHTUAN (04/11/2020)
 */
Route::middleware('api')->prefix('v2')->namespace('Api\Web')->group(function () {
    Route::prefix('news')->group(function () {
        Route::get('/', [
            'as' => 'news.read',
            'uses' => 'NewsApi@index'
        ]);

        Route::get('/id/{id}', [
            'as' => 'news.info',
            'uses' => 'NewsApi@show'
        ]);

        Route::get('/search', [
            'as' => 'news.search',
            'uses' => 'NewsApi@search'
        ]);
    });
});
