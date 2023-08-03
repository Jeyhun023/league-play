<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'App\Http\Controllers\Front\Home', 'as' => 'front::home.'], function () {
    Route::get('/', 'IndexController@index')->name('index');
    Route::get('/reset', 'IndexController@reset')->name('reset');
});

