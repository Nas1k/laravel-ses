<?php

Route::get('/list', '\Nas1k\LaravelSes\Http\Controllers\Report\ListController@create');
Route::post('/send', '\Nas1k\LaravelSes\Http\Controllers\EmailController@send');
