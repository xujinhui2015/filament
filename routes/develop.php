<?php

use App\Http\Controllers\Develop\TestController;
use Illuminate\Support\Facades\Route;

// 测试接口
Route::any('test', [TestController::class, 'index']);
