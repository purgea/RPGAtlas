<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return inertia('AtlasEditor');
});

Route::get('/play', function () {
    return inertia('AtlasPlayer');
})->name('atlas.play');
