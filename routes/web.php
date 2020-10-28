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
Route::get('/logout', 'AdminController@logout')->name('logout');
Route::post('/admin', 'AdminController@authenticate')->name('authenticate');

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->group(function () {
    Route::get('/home', [
        'as'=>'admin.home',
        'uses' => 'AdminController@index'
    ])->middleware('auth');
});
