# Access-Control Middleware Tests

## When To Use

Use this leaf for access-control middleware feature tests.

## Pattern

### File Shape

- Define a local `/_test` route inside the test.
- Attach the middleware under test directly by class or alias.
- Use Pest HTTP helpers such as `get`.
- Keep each test focused on one branch.


### Access Middleware Pattern

Cover:

- guest behavior;
- authenticated but unauthorized behavior;
- authorized behavior.

Use config overrides when the middleware reads allowlists or feature flags.

```php
it('forbids guests', function (): void {
    Route::middleware('example-access')
        ->get('/_test', fn (): string => 'ok');

    $response = get('/_test');

    $response->assertForbidden();
});

it('forbids actors without access', function (): void {
    config(['example.allowed_identifiers' => ['allowed@example.com']]);

    Route::middleware('example-access')
        ->get('/_test', fn (): string => 'ok');

    $actor = Actor::factory()->createOne(['email' => 'blocked@example.com']);

    login($actor);

    $response = get('/_test');

    $response->assertForbidden();
});

it('allows actors with access', function (): void {
    config(['example.allowed_identifiers' => ['allowed@example.com']]);

    Route::middleware('example-access')
        ->get('/_test', fn (): string => 'ok');

    $actor = Actor::factory()->createOne(['email' => 'allowed@example.com']);

    login($actor);

    $response = get('/_test');

    $response->assertOk();
});
```

## Related References

- [Parent router](../README.md)
