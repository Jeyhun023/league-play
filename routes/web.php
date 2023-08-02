<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'App\Http\Controllers\Front\Home', 'as' => 'front::home.'], function () {
    Route::get('/', 'IndexController@index')->name('home.index');
});

