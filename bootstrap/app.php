<?php

use App\Application\Shared\Exception\ForbiddenException;
use App\Application\Shared\Exception\NotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

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
        $exceptions->render(fn (\Throwable $e): JsonResponse => match(true) {
            $e instanceof NotFoundException => response()->json(
                data: ['error' => $e->getMessage()], status: Response::HTTP_NOT_FOUND),
            $e instanceof ForbiddenException => response()->json(
                data: ['error' => $e->getMessage()], status: Response::HTTP_FORBIDDEN),
            default => response()->json(
                data: ['error' => 'Something went wrong'], status: Response::HTTP_INTERNAL_SERVER_ERROR
            )
        });
    })->create();
