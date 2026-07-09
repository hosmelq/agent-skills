# Inertia Shared Props Middleware

## When To Use

Use this leaf for request-scoped Inertia shared props.

## Pattern

- Return resource-shaped auth data consistently with the local Inertia
  middleware.
- Guests receive explicit `null` values. Authenticated actors receive
  resource-shaped actor data, and `Workspace` data is present only for a
  current `Workspace`.
- Inertia modal support belongs to the frontend layout stack, not middleware
  state.

### Inertia Shared Props Example

```php
<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Inertia\Middleware;
use Override;

class ExampleInertiaRequests extends Middleware
{
    #[Override]
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => $this->authProperties($request),
        ];
    }

    /**
     * @return array{actor: null|JsonResource, workspace: null|JsonResource}
     */
    private function authProperties(Request $request): array
    {
        $actor = $request->user();

        return [
            'actor' => $actor?->toResource(),
            'workspace' => $actor?->currentWorkspace?->toResource(),
        ];
    }
}
```

## Related References

- [Parent router](../README.md)
