<?php

use Illuminate\Support\Facades\Route;

Route::get("/", 'AdminController@index');

Route::get("/login", 'AdminController@loginGet');
Route::post('/login', 'AdminController@login');
// Route::get("/register", 'AdminController@registerGet');
// Route::post("/register", 'AdminController@register');
Route::get('/logout', 'AdminController@logout');


Route::group(['prefix' => 'admin', 'middleware' => 'CheckSession'], function () {
    Route::get('dashboard', 'AdminController@dashboard');
    Route::post('sdsd', 'AdminController@getCountData');
    Route::get('dataK3', 'K3Controller@show');
    Route::get('pelapor', 'PelaporController@show');
    Route::get('pelapor/delete/{id}', 'PelaporController@delete');
    Route::get('logout', 'AdminController@logout');
    Route::group(['prefix' => 'dataAdmin'], function () {
        Route::get('/', 'AdminController@show');
        Route::post('/', 'AdminController@store');
        Route::get('/delete/{id}', 'AdminController@delete');
        Route::post('/edit', 'AdminController@editData');
        Route::post('/tolak/{id}', 'K3Controller@tolak');
        Route::post('/terima/{id}', 'K3Controller@terima');
        Route::post('/getDataSatu/{id}', 'K3Controller@getDataSatu');
    });
});
