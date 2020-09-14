<?php

use App\Events\TableUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin', 'middleware' => ['cors', 'assign.guard:admin']], function () {
    Route::post('register', 'AdminController@register');
    Route::post('login', 'AdminController@login');
    Route::group(['middleware' => ['jwt.auth', 'assign.guard:admin']], function () {
        Route::get('dataK3', 'K3Controller@index');
        Route::get('namaku', 'K3Controller@authData');
    });
});

Route::group(['prefix' => 'pelapor', 'middleware' => ['assign.guard:pelapor', 'cors']], function () {
    Route::post('register', 'PelaporController@register');
    Route::post('login', 'PelaporController@login');
    Route::group(['middleware' => ['jwt.auth', 'assign.guard:pelapor']], function () {
        Route::post('inputDataK3', 'K3Controller@PelaporInputData');
        Route::get('statusDataK3', 'K3Controller@PelaporCekStatus');
    });
});
