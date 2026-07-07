<?php

use App\Exceptions\BusinessException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

function errorResponse(
    string $message,
    int $statusCode,
    mixed $errors = null
) {
    return response()->json([
        'success' => false,
        'message' => $message,
        'data' => null,
        'errors' => $errors,
    ], $statusCode);
}

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        $exceptions->render(function (BusinessException $exception) {

            return errorResponse(
                $exception->getMessage(),
                $exception->getStatusCode()
            );

        });

        $exceptions->render(function (ValidationException $exception) {

            return errorResponse(
                'Validation failed.',
                422,
                $exception->errors()
            );

        });

        $exceptions->render(function (NotFoundHttpException $exception) {

            return errorResponse(
                'Resource not found.',
                404
            );

        });

        $exceptions->render(function (\Throwable $exception) {

            return errorResponse(
                config('app.debug')
                    ? $exception->getMessage()
                    : 'Something went wrong.',
                500
            );

        });

    })
    ->create();