<?php

use App\Http\Middleware\IsAdmin;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
         // Register custom middleware
         $middleware->alias([
            'isAdmin' => IsAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        
        $exceptions->render(function (Illuminate\Auth\AuthenticationException $e, $request) {
            
            $guard = data_get($e->guards(), 0);
            switch ($guard) {
                case 'admin':
                    $login = 'admin.login';
                    break;
    
                default:
                    $login = 'login-signup';
                    break;
            }
    
            return $request->expectsJson()
                ? response()->json(['message' => $e->getMessage()], 401)
                : redirect()->guest(route($login));
        });

    })->create();
