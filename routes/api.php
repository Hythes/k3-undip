<?php

use App\Events\TableUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post("/check", function () {
    return response()->json(['message' => 'work!']);
});

Route::group(['prefix' => 'admin', 'middleware' => ['cors', 'assign.guard:admin']], function () {
    Route::post('register', 'AdminController@register');
    Route::post('login', 'AdminController@login');

    Route::group(['middleware' => ['jwt.auth', 'assign.guard:admin']], function () {
        Route::group(['prefix' => 'k3'], function () {
            Route::get("getData", 'K3Controller@getData');
            Route::get('getDataSatu/{id}', 'K3Controller@getDataSatu');
            Route::post("inputData", 'K3Controller@PelaporInputData');
            Route::post("editData/{id}", 'K3Controller@editData');
            Route::delete("hapusData/{id}", 'K3Controller@delete');
        });
        Route::group(['prefix' => 'dataAdmin'], function () {
            Route::get("getData", 'AdminController@getData');
            Route::get('getDataSatu/{id}', 'AdminController@getDataSatu');
            Route::post("inputData", 'AdminController@register');
            Route::post("editData/{id}", 'AdminController@editData');
            Route::delete("hapusData/{id}", 'AdminController@delete');
        });
        Route::group(['prefix' => 'pelapor'], function () {
            Route::get("getData", 'PelaporController@getData');
            Route::get('getDataSatu/{id}', 'PelaporController@getDataSatu');
            Route::post("inputData", 'PelaporController@inputData');
            Route::post("editData/{id}", 'PelaporController@editData');
            Route::delete("hapusData/{id}", 'PelaporController@delete');
        });

        // Route::group(['prefix' => 'registerCode'], function () {
        //     Route::get("getData", 'RegistrationCodeController@getData');
        //     Route::get('getDataSatu/{id}', 'RegistrationCodeController@getDataSatu');
        //     Route::post("inputData", 'RegistrationCodeController@inputData');
        //     Route::post("editData/{id}", 'RegistrationCodeController@editData');
        //     Route::delete("hapusData/{id}", 'RegistrationCodeController@delete');
        // });
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
