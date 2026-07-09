# Request Access-Code Validation

## When To Use

Use this leaf for the public access-code request validation dataset.

## Pattern

### `POST /api/session-codes/request` (`api.sessions.code.request`)

```php
it('validates fields', function (array $data, array $expected): void {
    $response = postJson(route('api.sessions.code.request'), $data);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors($expected);
})->with([
    'email' => [
        'data' => [
            'recipient_email' => 'invalid',
        ],
        'expected' => [
            'recipient_email' => 'The recipient email field must be a valid email address.',
        ],
    ],
    'email:dns' => [
        'data' => [
            'recipient_email' => 'test@example.invalid',
        ],
        'expected' => [
            'recipient_email' => 'The recipient email field must be a valid email address.',
        ],
    ],
    'email:strict' => [
        'data' => [
            'recipient_email' => 'test()@example.test',
        ],
        'expected' => [
            'recipient_email' => 'The recipient email field must be a valid email address.',
        ],
    ],
    'indisposable' => [
        'data' => [
            'recipient_email' => 'test@discarded.example',
        ],
        'expected' => [
            'recipient_email' => "This email address can't be used. Please try a different email.",
        ],
    ],
    'max:255 (string)' => [
        'data' => [
            'recipient_email' => str_repeat('a', 256),
        ],
        'expected' => [
            'recipient_email' => 'The recipient email field must not be greater than 255 characters.',
        ],
    ],
    'required' => [
        'data' => [],
        'expected' => [
            'recipient_email' => 'The recipient email field is required.',
        ],
    ],
]);
```

## Related References

- [Parent router](../api-login-validation.md)
