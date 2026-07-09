# Inertia Shared Props Middleware Tests

## When To Use

Use this leaf for request-scoped Inertia shared-prop tests.

## Pattern

### Inertia Middleware Pattern

For Inertia sharing middleware:

- define a route that renders an Inertia component;
- call the route as guest and authenticated actor;
- assert shared props through `assertInertia`;
- assert nested public ids for shared auth/actor/`Workspace` data.

```php
it('shares null authentication data for guests', function (): void {
    Route::middleware(ExampleInertiaRequests::class)
        ->get('/_test', fn () => Inertia::render('example/Page'));

    $response = get('/_test');

    $response->assertOk()
        ->assertInertia(function (AssertableInertia $page): void {
            $page
                ->where('auth.actor', null)
                ->where('auth.workspace', null);
        });
});

it('shares authenticated actor details', function (): void {
    Route::middleware(ExampleInertiaRequests::class)
        ->get('/_test', fn () => Inertia::render('example/Page'));

    $workspace = Workspace::factory()->createOne();
    $actor = login(workspace: $workspace);

    $response = get('/_test');

    $response->assertOk()
        ->assertInertia(function (AssertableInertia $page) use ($actor, $workspace): void {
            $page
                ->where('auth.actor.id', $actor->public_id)
                ->where('auth.workspace.id', $workspace->public_id);
        });
});
```

## Related References

- [Parent router](../README.md)
