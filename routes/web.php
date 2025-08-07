<?php

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;


// 接口文档授权
Route::get('/docs/{file?}', function ($file = 'index.html') {
    // 非管理员禁止访问文档
    if (!auth()->user()->isAdmin()) {
        abort(403);
    }

    $path = base_path('docs/' . $file);

    if (!File::exists($path)) {
        abort(404);
    }

    return Response::file($path);
})->where('file', '.*');
