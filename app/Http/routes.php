<?php

Route::get('/list', 'Report\ListController@create');
Route::post('/send', 'EmailController@send');
