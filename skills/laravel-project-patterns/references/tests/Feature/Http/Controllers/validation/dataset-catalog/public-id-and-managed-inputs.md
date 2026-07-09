# Public ID and Managed Input Dataset Cases

## When To Use

Use for nullable move-after public IDs, body-level public IDs accepted by other
relationship endpoints, and payload keys explicitly rejected as server-managed.

## Pattern

### Managed Input Cases

```php
[
    'missing' => [
        'data' => fn (): array => [
            'server_managed_value' => 'submitted',
        ],
        'expected' => [
            'server_managed_value' => 'The server managed value field must be missing.',
        ],
    ],
    'prohibited' => [
        'data' => fn (): array => [
            'immutable_related_record_id' => 'related-record-id',
        ],
        'expected' => [
            'immutable_related_record_id' => 'The immutable related record id field is prohibited.',
        ],
    ],
]
```

### Move-After Case

The move endpoint has one field, so its dataset key is the rule name:

```php
[
    'string' => [
        'data' => fn (): array => [
            'move_after_id' => 123,
        ],
        'expected' => [
            'move_after_id' => 'The move after id field must be a string.',
        ],
    ],
]
```

### Body-Level Relationship Cases

These belong only to a different endpoint that accepts `record_id` in the
payload:

```php
[
    'required' => [
        'data' => fn (): array => [],
        'expected' => [
            'record_id' => 'The record id field is required.',
        ],
    ],
    'string' => [
        'data' => fn (): array => [
            'record_id' => 456,
        ],
        'expected' => [
            'record_id' => 'The record id field must be a string.',
        ],
    ],
]
```

For a move endpoint, the target public ID is supplied by route binding and is
not a `record_id` payload case. Keep the `record_id` cases only for a different
endpoint that genuinely accepts a body-level relationship ID. Keep the
`Rule::notIn(...)` self-move failure as a named test in the focused move
reference instead of inventing a `different`-style dataset key.

Use `missing` only when the field appears in request rules. When
`FormRequest::failOnUnknownFields()` owns rejection of an unknown key, leave
the key out of `rules()` and test the application bootstrap contract instead
of inventing `exclude`.

## Related References

- [`../dataset-catalog.md`](../dataset-catalog.md)
- [`../store-validates-fields.md`](../store-validates-fields.md)
- [`../update-validates-fields.md`](../update-validates-fields.md)
- [`references/tests/Feature/Http/Controllers/pattern-catalog/move.md`](../../pattern-catalog/move.md)
