<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'App\Http\Controllers\Api', 'as' => 'api::game.'], function () {
    Route::get('/play', 'GameController@play')->name('play');
    Route::get('/play-all', 'GameController@playAll')->name('playAll');
});
