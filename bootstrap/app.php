<?php
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(web: __DIR__.'/../routes/web.php', api: __DIR__.'/../routes/api.php')
    ->withMiddleware(function ($middleware) {
        $middleware->redirectGuestsTo(fn (Request $r) => $r->is('api/*') ? null : '/');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->shouldRenderJsonWhen(fn (Request $r, Throwable $e) =>
            $r->is('api/*') || $r->expectsJson());
    })->create();
