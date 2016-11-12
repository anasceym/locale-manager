<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('home', 'HomeController@index');

Route::group([
    'middleware' => 'auth'
], function() {

    Route::get('projects', 'ProjectsController@index')->name('projects.index');
    Route::get('projects/new', 'ProjectsController@create')->name('projects.new');

    Route::get('passport', function() {
        return view('passport.index');
    });
});

Auth::routes();
