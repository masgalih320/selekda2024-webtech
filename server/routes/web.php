<?php

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/media/{path}', function (Request $request, $path) {
    $disk = Storage::disk('public');

    if ($disk->exists('media/' . $path)) {
        return response($disk->get('media/' . $path))->header('Content-Type', "");
    }

    return abort(404);
})
    ->where('path', '.+')
    ->name('media');
