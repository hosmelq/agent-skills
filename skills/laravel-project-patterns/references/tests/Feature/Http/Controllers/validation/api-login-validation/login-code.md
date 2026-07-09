# Access-Code Login Validation

## When To Use

Use this leaf for the public access-code login validation dataset.

## Pattern

### `POST /api/session-codes/login` (`api.sessions.code.login`)

```php
it('validates fields', function (array $data, array $expected): void {
    $response = postJson(route('api.sessions.code.login'), $data);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors($expected);
})->with([
    'digits:6' => [
        'data' => [
            'recipient_email' => 'actor@example.test',
            'code' => '12345',
        ],
        'expected' => [
            'code' => 'The code field must be 6 digits.',
        ],
    ],
    'email' => [
        'data' => [
            'recipient_email' => 'invalid',
        ],
        'expected' => [
            'recipient_email' => 'The recipient email field must be a valid email address.',
        ],
    ],
    'exists' => [
        'data' => [
            'code' => '111111',
        ],
        'expected' => [
            'code' => 'The selected code is invalid.',
        ],
    ],
    'required' => [
        'data' => [],
        'expected' => [
            'code' => 'The code field is required.',
            'recipient_email' => 'The recipient email field is required.',
        ],
    ],
]);
```

## Related References

- [Parent router](../api-login-validation.md)
