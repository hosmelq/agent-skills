# Protected Current Actor

## When To Use

Read this focused reference when the task involves protected current actor.

## Pattern

### Protected Current Actor

Use the framework's authenticated-actor injection boundary when the project
does so, then return the resource directly:

```php
<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Resources\ActorResource;
use App\Models\Actor;
use Illuminate\Container\Attributes\CurrentUser;

class CurrentActorController
{
    public function show(#[CurrentUser] Actor $actor): ActorResource
    {
        return ActorResource::make($actor);
    }
}
```

Register the route with `[CurrentActorController::class, 'show']`. The route
owns `auth:sanctum`; the corresponding feature tests prove guest `401`,
authenticated success, and public-ID serialization.

## Related References

- [`../api-authentication.md`](../api-authentication.md)
