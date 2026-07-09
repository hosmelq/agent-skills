# Store Base Validation Dataset

## When To Use

Use this composite template only when assembling several generic store rules.
For one rule, select its focused leaf from
[`dataset-catalog.md`](../dataset-catalog.md).

## Pattern

```php
it('validates fields', function (array $data, array $expected): void {
    $workspace = Workspace::factory()->createOne();

    login(workspace: $workspace);

    $response = post(route('workspaces.parent-records.store', [
        'workspace' => $workspace,
    ]), $data);

    $response->assertRedirectBackWithErrors($expected);
})->with([
    'array' => [
        'data' => [
            'schedule' => 'not-array',
        ],
        'expected' => [
            'schedule' => 'The schedule field must be an array.',
        ],
    ],
    'boolean' => [
        'data' => [
            'is_default' => 'not-boolean',
        ],
        'expected' => [
            'is_default' => 'The is default field must be true or false.',
        ],
    ],
    'decimal:0,4' => [
        'data' => [
            'minimum_value' => '12.12345',
            'maximum_value' => '15.12345',
        ],
        'expected' => [
            'minimum_value' => 'The minimum value field must have 0-4 decimal places.',
            'maximum_value' => 'The maximum value field must have 0-4 decimal places.',
        ],
    ],
    'email' => [
        'data' => [
            'contact_email' => 'invalid',
        ],
        'expected' => [
            'contact_email' => 'The contact email field must be a valid email address.',
        ],
    ],
    'enum' => [
        'data' => [
            'example_mode' => 'invalid',
        ],
        'expected' => [
            'example_mode' => 'The selected example mode is invalid.',
        ],
    ],
    'exists' => [
        'data' => [
            'related_record_id' => 'not-a-public-id',
        ],
        'expected' => [
            'related_record_id' => 'The selected related record id is invalid.',
        ],
    ],
    'max:255 (string)' => [
        'data' => [
            'name' => Str::repeat('a', 256),
        ],
        'expected' => [
            'name' => 'The name field must not be greater than 255 characters.',
        ],
    ],
    'missing' => [
        'data' => [
            'server_managed_value' => 'submitted',
        ],
        'expected' => [
            'server_managed_value' => 'The server managed value field must be missing.',
        ],
    ],
    'numeric' => [
        'data' => [
            'minimum_value' => 'invalid',
        ],
        'expected' => [
            'minimum_value' => 'The minimum value field must be a number.',
        ],
    ],
    'required' => [
        'data' => [],
        'expected' => [
            'name' => 'The name field is required.',
        ],
    ],
    'required_if' => [
        'data' => [
            'example_mode' => 'advanced',
        ],
        'expected' => [
            'conditional_value' => 'The conditional value field is required.',
        ],
    ],
]);
```

## Related References

- [Parent router](../store-validates-fields.md)
