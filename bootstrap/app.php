<?php

use App\Enums\HttpCodeEnum;
use App\Support\Exceptions\ResponseException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Jiannei\Response\Laravel\Support\Facades\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (ValidationException $e, Request $request) {
            // 响应API表单提交验证错误
            if ($request->is('api/*')) {
                return Response::errorBadRequest($e->getMessage());
            }
            return true;
        });

        $exceptions->render(function (RouteNotFoundException $e, Request $request) {
            // 响应API表单提交验证错误
            if ($request->is('api/*')) {
                return Response::fail(HttpCodeEnum::TokenFail->getLabel(), HttpCodeEnum::TokenFail);
            }
            return true;
        });

        // 业务异常处理
        $exceptions->render(function (ResponseException $e) {
            return Response::fail($e->getMessage());
        });

    })->create();
