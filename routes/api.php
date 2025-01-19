<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

// This is typically found in routes/web.php or routes/api.php
//Route::post('/graphiql', 'GraphQLController@query');

