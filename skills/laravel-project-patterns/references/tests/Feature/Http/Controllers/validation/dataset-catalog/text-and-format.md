# Text and Format Dataset Cases

## When To Use

Merge only the cases whose exact text or format rules occur in the request.
Keep distinct length parameters because each protects a different contract.

## Pattern

```php
[
    'email' => [
        'data' => [
            'contact_email' => 'person@',
        ],
        'expected' => [
            'contact_email' => 'The contact email field must be a valid email address.',
        ],
    ],
    'hex color' => [
        'data' => [
            'color' => 'blue',
        ],
        'expected' => [
            'color' => 'The color field format is invalid.',
        ],
    ],
    'max:3 (string)' => [
        'data' => [
            'region_code' => Str::repeat('a', 4),
        ],
        'expected' => [
            'region_code' => 'The region code field must not be greater than 3 characters.',
        ],
    ],
    'max:20 (numeric and string)' => [
        'data' => [
            'code_length' => 21,
            'code_prefix' => Str::repeat('a', 21),
        ],
        'expected' => [
            'code_length' => 'The code length field must not be greater than 20.',
            'code_prefix' => 'The code prefix field must not be greater than 20 characters.',
        ],
    ],
    'max:2000 (string)' => [
        'data' => [
            'description' => Str::repeat('a', 2001),
        ],
        'expected' => [
            'description' => 'The description field must not be greater than 2000 characters.',
        ],
    ],
    'min:4 (numeric)' => [
        'data' => [
            'code_length' => 1,
        ],
        'expected' => [
            'code_length' => 'The code length field must be at least 4.',
        ],
    ],
    'phone' => [
        'data' => [
            'contact_number' => '+503 8888 8888',
        ],
        'expected' => [
            'contact_number' => 'The contact number field must be a valid number.',
        ],
    ],
    'phone (country_code)' => [
        'data' => [
            'country_code' => 'NI',
            'contact_number' => '8888',
        ],
        'expected' => [
            'contact_number' => 'The contact number field must be a valid number.',
        ],
    ],
    'string' => [
        'data' => [
            'related_record_id' => 123,
        ],
        'expected' => [
            'related_record_id' => 'The related record id field must be a string.',
        ],
    ],
    'timezone' => [
        'data' => [
            'timezone' => 'invalid',
        ],
        'expected' => [
            'timezone' => 'The timezone field must be a valid timezone.',
        ],
    ],
]
```

The baseline catalogs separately preserve `max:255`, `email:dns`,
`email:strict`, and indisposable-email cases. Merge those when their rules are
present instead of duplicating them here.

## Related References

- [`../dataset-catalog.md`](../dataset-catalog.md)
- [`../store-validates-fields.md`](../store-validates-fields.md)
- [`../update-validates-fields.md`](../update-validates-fields.md)
