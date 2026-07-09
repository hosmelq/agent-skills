# Access-Control Middleware

## When To Use

Use this leaf for access-control middleware shape and behavior.

## Pattern

### Middleware Shape

- Keep middleware focused on request/response behavior.
- For access middleware, keep guest, non-authorized, and authorized behavior explicit.
- Access middleware should abort with the exact HTTP status used by the live middleware, such as `403` for access-denied branches.
- Middleware aliases, guest/auth redirects, API throttling, and web middleware append order are configured in the application bootstrap. Read the bootstrap middleware configuration before changing middleware names, aliases, or shared Inertia behavior.


### Access Middleware Example

```php
<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureExampleAccess
{
    /**
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        abort_if(
            $request->user() === null || ! $request->user()->hasExampleAccess(),
            Response::HTTP_FORBIDDEN
        );

        return $next($request);
    }
}
```

## Related References

- [Parent router](../README.md)
