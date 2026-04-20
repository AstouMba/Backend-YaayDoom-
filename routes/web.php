<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/docs');
});

Route::get('/openapi.json', function () {
    $path = storage_path('api-docs/api-docs.json');

    abort_unless(is_file($path), 404);

    return response()->file($path, [
        'Content-Type' => 'application/json; charset=utf-8',
    ]);
});
