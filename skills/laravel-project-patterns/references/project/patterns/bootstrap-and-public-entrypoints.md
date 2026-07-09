# Bootstrap And Public Entrypoints

## When To Use

Read this focused reference when the task involves bootstrap and public entrypoints.

## Pattern

### Bootstrap And Public Entrypoints

Keep bootstrap and public entrypoints thin. They should wire framework routing, middleware aliases, exception integration, and request handling. Do not put application business logic in `bootstrap/*.php` or `public/*.php`.

```php
<?php

declare(strict_types=1);

use App\Http\Middleware\ExamplePageMiddleware;
use App\Support\ExampleExceptionIntegration;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware
            ->alias([
                'example-admin' => ExamplePageMiddleware::class,
            ])
            ->redirectGuestsTo(fn (): string => route('login'))
            ->redirectUsersTo('/')
            ->throttleApi()
            ->web(append: [
                ExamplePageMiddleware::class,
            ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        ExampleExceptionIntegration::handles($exceptions);
    })->create();
```

For long-running server entrypoints, set only required server path defaults and then require the framework worker. Keep request-specific state out of static variables and globals.

## Related References

- [`../README.md`](../README.md)
