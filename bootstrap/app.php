<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );

        $exceptions->render(function (\Illuminate\Validation\ValidationException $e, Request $request) {
            return response()->json([
                'success' => false,
                'message' => 'Los datos proporcionados no son válidos',
                'errors' => $e->errors()
            ], 422);
        });

        $exceptions->render(function (\Illuminate\Database\Eloquent\ModelNotFoundException $e, Request $request) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage() ?: 'Recurso no encontrado',
                'errors' => null
            ], 404);
        });

        $exceptions->render(function (\Illuminate\Database\QueryException $e, Request $request) {
            if ($e->getCode() === '22P02' || str_contains($e->getMessage(), 'invalid input syntax for type uuid')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Recurso no encontrado',
                    'errors' => null
                ], 404);
            }
        });

        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, Request $request) {
            return response()->json([
                'success' => false,
                'message' => 'Recurso o ruta no encontrada',
                'errors' => null
            ], 404);
        });

        $exceptions->render(function (\InvalidArgumentException $e, Request $request) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => null
            ], 400);
        });

        $exceptions->render(function (\Throwable $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                $isDebug = config('app.debug');
                return response()->json([
                    'success' => false,
                    'message' => $isDebug ? $e->getMessage() : 'Error interno del servidor',
                    'errors' => $isDebug ? [
                        'exception' => get_class($e),
                        'file' => $e->getFile(),
                        'line' => $e->getLine()
                    ] : null
                ], 500);
            }
        });
    })->create();
