# Routes

## When To Use

Read this focused reference when the task involves routes.

## Pattern

### Routes

Keep route files declarative. Group names, prefixes, middleware, and scoped bindings before route declarations. Use controller classes directly for invokable routes, `[Controller::class, 'method']` for method routes, and named resource routes for controller feature-test contracts.

```php
<?php

declare(strict_types=1);

use App\Http\Controllers\Api\CurrentActorController;
use App\Http\Controllers\Api\SessionCodeController;
use App\Http\Controllers\ExampleRecordController;
use App\Http\Controllers\ExampleRecordLifecycleController;
use App\Http\Controllers\WorkspaceSettingsController;
use Illuminate\Support\Facades\Route;

Route::name('api.')->group(function (): void {
    Route::name('sessions.')->prefix('sessions')->group(function (): void {
        Route::post('code/request', SessionCodeController::class)
            ->middleware(['throttle:sessions.code.request'])
            ->name('code.request');
    });

    Route::middleware('auth:sanctum')->group(function (): void {
        Route::get('actor', [CurrentActorController::class, 'show'])
            ->name('actor.show');
    });
});

Route::middleware('auth')->group(function (): void {
    Route::middleware('verified')->scopeBindings()->group(function (): void {
        Route::get('workspaces/{workspace}/settings/general', [WorkspaceSettingsController::class, 'show'])
            ->name('workspaces.settings.general');

        Route::singleton('workspaces.example-records.lifecycle', ExampleRecordLifecycleController::class)
            ->creatable()
            ->only(['destroy', 'store']);

        Route::resource('workspaces.example-records', ExampleRecordController::class);
    });
});
```

Route names, route parameter order, and `scopeBindings()` are part of the controller feature-test contract. If a route changes, update the matching controller tests and controller reference examples.

Console route files may schedule framework/application commands. Keep schedules declarative and use the narrow cadence the operational contract needs:

```php
<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schedule;

Schedule::command('reference-data:update')->weekly();

Schedule::command('health:check')->everyMinute();
Schedule::command('health:queue-check-heartbeat')->everyMinute();
Schedule::command('health:schedule-check-heartbeat')->everyMinute();

Schedule::command('model:prune')->daily();
```

## Related References

- [`../README.md`](../README.md)
