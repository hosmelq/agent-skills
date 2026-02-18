# Update Validation Dataset Snippets

This file is a **catalog**. Action templates already include a baseline `validates fields` test.
Merge only the extra rules your action actually uses.

Conventions (match sibling controller tests first):

- Keep each dataset `data` minimal (only the field(s) needed to trigger that rule). Avoid repeating a full valid payload per case.
- Keep `expected` consistent within a `->with([...])` block (either messages everywhere or keys everywhere).

Use an authenticated **authorized in-scope actor** for validation tests; otherwise authorization may return `403` before validation runs.

Load focused files first when applicable:

- `references/validation/required-with-and-array.md`
- `references/validation/scoped-exists-and-unique.md`
- `references/validation/prepare-for-validation.md` (preferred for stored-value-dependent validation)

```php
it('validates fields', function (array $data, array $expected): void {
    $organization = Organization::factory()->createOne();

    login(organization: $organization);

    $response = patch(route('organizations.update', [
        'organization' => $organization,
    ]), $data);

    $response->assertRedirectBackWithErrors($expected);
})->with([
    'boolean' => [
        'data' => [
            'is_default' => 'not_boolean',
        ],
        'expected' => [
            'is_default' => 'The is default field must be true or false.',
        ],
    ],
    'decimal:0,4' => [
        'data' => [
            'minimum_weight' => 12.12345,
            'maximum_weight' => 15.12345,
        ],
        'expected' => [
            'minimum_weight' => 'The minimum weight field must have 0-4 decimal places.',
            'maximum_weight' => 'The maximum weight field must have 0-4 decimal places.',
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
    'enum' => [
        'data' => [
            'country_code' => 'invalid',
        ],
        'expected' => [
            'country_code' => 'The selected country code is invalid.',
        ],
    ],
    'exists' => [
        'data' => [
            'country_code' => 'NI',
            'province_code' => 'XX',
        ],
        'expected' => [
            'province_code' => 'The selected province code is invalid.',
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
    'max:3 (string)' => [
        'data' => [
            'province_code' => Str::repeat('a', 4),
        ],
        'expected' => [
            'province_code' => 'The province code field must not be greater than 3 characters.',
        ],
    ],
    'min:4 (string)' => [
        'data' => [
            'locker_code_format_length' => 'a',
        ],
        'expected' => [
            'locker_code_format_length' => 'The locker code format length field must be at least 4.',
        ],
    ],
    'numeric' => [
        'data' => [
            'locker_code_format_length' => 'invalid',
        ],
        'expected' => [
            'locker_code_format_length' => 'The locker code format length field must be a number.',
        ],
    ],
    'phone' => [
        'data' => [
            'phone_number' => '8888',
        ],
        'expected' => [
            'phone_number' => 'The phone number field must be a valid number.',
        ],
    ],
    'phone (country_code)' => [
        'data' => [
            'phone_number' => '+503 8888 8888',
        ],
        'expected' => [
            'phone_number' => 'The phone number field must be a valid number.',
        ],
    ],
    'sometimes (required)' => [
        'data' => [
            'email' => '',
        ],
        'expected' => [
            'email' => 'The email field is required.',
        ],
    ],
]);
```

```php
it('validates email uniqueness within the same scope on update', function (): void {
    $organization1 = Organization::factory()->createOne([
        'email' => 'john@gmail.com',
    ]);

    $organization2 = Organization::factory()->createOne([
        'email' => 'jane@gmail.com',
    ]);

    login();

    $response = patch(route('organizations.update', [
        'organization' => $organization2,
    ]), [
        'email' => 'john@gmail.com',
    ]);

    $response->assertRedirectBackWithErrors([
        'email' => 'The email has already been taken.',
    ]);
});
```

```php
it('validates minimum against stored maximum', function (): void {
    $record = Record::factory()->createOne([
        'minimum_value' => 5,
        'maximum_value' => 10,
    ]);

    login();

    $response = patch(route('organizations.records.update', [
        'organization' => $record->organization,
        'record' => $record,
    ]), [
        'minimum_value' => 11,
    ]);

    $response->assertRedirectBackWithErrors([
        'minimum_value' => 'The minimum value field must be less than or equal to 10.',
    ]);
});
```
