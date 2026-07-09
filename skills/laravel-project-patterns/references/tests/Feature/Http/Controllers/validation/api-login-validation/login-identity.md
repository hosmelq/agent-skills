# External Identity Login Validation

## When To Use

Use this leaf for the public external identity login validation dataset.

## Pattern

### `POST /api/session-identities/login` (`api.sessions.identity.login`)

```php
it('validates fields', function (array $data, array $expected): void {
    $response = postJson(route('api.sessions.identity.login'), $data);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors($expected);
})->with([
    'max:255 (string)' => [
        'data' => [
            'first_name' => str_repeat('a', 256),
            'last_name' => str_repeat('b', 256),
        ],
        'expected' => [
            'first_name' => 'The first name field must not be greater than 255 characters.',
            'last_name' => 'The last name field must not be greater than 255 characters.',
        ],
    ],
    'required' => [
        'data' => [],
        'expected' => [
            'id_token' => 'The id token field is required.',
            'nonce' => 'The nonce field is required.',
        ],
    ],
]);
```

## Related References

- [Parent router](../api-login-validation.md)
