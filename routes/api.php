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

Route::group([
    'middleware' => ['auth:api']
], function() {

    Route::get('/locales', 'Api\LocalesController@index')
        ->name('api.locales.index');

    Route::get('/locales/{code}/name', 'Api\LocalesController@getNameByCode')
        ->name('api.locales.name');

    Route::group(['prefix' => 'projects'], function() {

        Route::post('/{project}/import/{type}', 'Api\ProjectsController@import')
            ->name('api.projects.import');

        Route::delete('/{project}/lang/{project_lang}', 'Api\ProjectsController@deleteLang')
            ->name('api.projects.lang.delete');

        Route::post('/{project}/lang', 'Api\ProjectsController@postLang')
            ->name('api.projects.lang.create');

        Route::get('/{project}/lang', 'Api\ProjectsController@getLang')
            ->name('api.projects.lang');

        Route::get('/{project}/namespaces/{namespace}', 'Api\ProjectsController@showNamespace')
            ->name('api.projects.namespaces.show');

        Route::delete('/{project}/namespaces/{namespace}', 'Api\ProjectsController@deleteNamespace')
            ->name('api.projects.namespaces.delete');

        Route::patch('/{project}/namespaces/{namespace}', 'Api\ProjectsController@updateNamespace')
            ->name('api.projects.namespaces.update');

        Route::post('/{project}/namespaces', 'Api\ProjectsController@createNamespace')
            ->name('api.projects.namespaces.create');

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
});
