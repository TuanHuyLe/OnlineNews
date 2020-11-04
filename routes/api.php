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
Route::middleware('api')->prefix('v1')->namespace('Api\Admin')->group(function () {
    Route::prefix('categories')->group(function () {
        Route::get('/', [
            'as' => 'categories.read',
            'uses' => 'CategoryApi@index'
        ]);

        Route::post('/', [
            'as' => 'categories.create',
            'uses' => 'CategoryApi@store'
        ]);
    });
});

/**
 * Define api web
 * api/v1/[entity]
 * CreatedBy: LHTUAN (04/11/2020)
 */
Route::middleware('api')->prefix('v1')->namespace('Api\Web')->group(function () {
    Route::prefix('news')->group(function () {
        Route::get('/', [
            'as' => 'news.read',
            'uses' => 'NewsApi@index'
        ]);

        Route::post('/', [
            'as' => 'news.create',
            'uses' => 'NewsApi@store'
        ]);
    });
});
