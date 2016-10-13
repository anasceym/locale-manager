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

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::get('/locales', 'Api\LocalesController@index')
    ->name('api.locales.index');
//    ->middleware('auth:api');

Route::get('/locales/{code}/name', 'Api\LocalesController@getNameByCode')
    ->name('api.locales.name');

Route::group(['prefix' => 'projects'], function() {

    Route::get('/{project}', 'Api\ProjectsController@show')
        ->name('api.projects.show');

    Route::delete('/{project}', 'Api\ProjectsController@destroy')
        ->name('api.projects.delete');

    Route::patch('/{project}', 'Api\ProjectsController@update')
        ->name('api.projects.update');

    Route::get('/', 'Api\ProjectsController@index')
        ->name('api.projects.index');

    Route::post('/', 'Api\ProjectsController@handleCreate')
        ->name('api.projects.create');

});