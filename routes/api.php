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

//Route::middleware('auth:api')->get('user', function (Request $request) {
//    return $request->user();
//});

Route::group(['prefix' => 'v1'],function(){
    Route::post('login', 'ApiController@login')->name('login');

    Route::group([
      'middleware' => 'auth:api'
    ], function() {
        Route::get('logout', 'ApiController@logout');
        Route::get('user', 'ApiController@user');
        Route::get('menu', 'ApiController@menu');
        Route::get('menuready', 'ApiController@menuready');
        Route::get('menucategory/{cat}', 'ApiController@menucategory');
        Route::get('categorymenu', 'ApiController@categorymenu');
        Route::get('pengeluaran', 'ApiController@pengeluaran');
        Route::get('pengeluaranbyid/{id}', 'ApiController@pengeluaranbyid');
        Route::post('pengeluaran', 'ApiController@inputpengeluaran');
        Route::post('updatepengeluaran', 'ApiController@updatepengeluaran');
        Route::post('deletepengeluaran', 'ApiController@deletepengeluaran');
    });
});
