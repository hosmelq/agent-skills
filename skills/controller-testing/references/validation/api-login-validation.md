# API Login Validation Snippets (Public Endpoints)

Use these for public API authentication endpoints. Do not add `requires authentication` by default for these routes.

## `POST /api/auth/email/request` (`api.auth.email.request`)

```php
it('validates fields', function (array $data, array $expected): void {
    $response = postJson(route('api.auth.email.request'), $data);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors($expected);
})->with([
    'email' => [
        'data' => [
            'email' => 'test@',
        ],
        'expected' => [
            'email' => 'The email field must be a valid email address.',
        ],
    ],
    'email:dns' => [
        'data' => [
            'email' => 'test@site.test',
        ],
        'expected' => [
            'email' => 'The email field must be a valid email address.',
        ],
    ],
    'email:strict' => [
        'data' => [
            'email' => 'test()@site.test',
        ],
        'expected' => [
            'email' => 'The email field must be a valid email address.',
        ],
    ],
    'indisposable' => [
        'data' => [
            'email' => 'test@0-mail.com',
        ],
        'expected' => [
            'email' => 'Disposable email addresses are not allowed.',
        ],
    ],
    'max:255 (string)' => [
        'data' => [
            'email' => str_repeat('a', 256),
        ],
        'expected' => [
            'email' => 'The email field must not be greater than 255 characters.',
        ],
    ],
    'required' => [
        'data' => [],
        'expected' => [
            'email' => 'The email field is required.',
        ],
    ],
]);
```

## `POST /api/auth/email/login` (`api.auth.email.login`)

```php
it('validates fields', function (array $data, array $expected): void {
    $response = postJson(route('api.auth.email.login'), $data);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors($expected);
})->with([
    'digits:6' => [
        'data' => [
            'email' => 'john@example.com',
            'code' => '12345',
        ],
        'expected' => [
            'code' => 'The code field must be 6 digits.',
        ],
    ],
    'email' => [
        'data' => [
            'email' => 'test@',
        ],
        'expected' => [
            'email' => 'The email field must be a valid email address.',
        ],
    ],
    'email:dns' => [
        'data' => [
            'email' => 'test@site.test',
        ],
        'expected' => [
            'email' => 'The email field must be a valid email address.',
        ],
    ],
    'email:strict' => [
        'data' => [
            'email' => 'test()@site.test',
        ],
        'expected' => [
            'email' => 'The email field must be a valid email address.',
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
    'indisposable' => [
        'data' => [
            'email' => 'test@0-mail.com',
        ],
        'expected' => [
            'email' => 'Disposable email addresses are not allowed.',
        ],
    ],
    'max:255 (string)' => [
        'data' => [
            'email' => str_repeat('a', 256),
        ],
        'expected' => [
            'email' => 'The email field must not be greater than 255 characters.',
        ],
    ],
    'required' => [
        'data' => [],
        'expected' => [
            'code' => 'The code field is required.',
            'email' => 'The email field is required.',
        ],
    ],
]);
```
