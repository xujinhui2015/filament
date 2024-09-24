<?php

use App\Http\Controllers\Develop\DocsController;
use Illuminate\Support\Facades\Route;

// 接口文档
Route::get('docs', [DocsController::class, 'index']);
