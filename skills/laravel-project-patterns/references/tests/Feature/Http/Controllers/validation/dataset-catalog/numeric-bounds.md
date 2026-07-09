# Numeric Bounds Dataset Cases

## When To Use

Use for numeric type, decimal precision, absolute bounds, or comparisons with
another submitted field. Include both directions when minimum and maximum
fields constrain each other.

## Pattern

```php
[
    'decimal:0,2' => [
        'data' => [
            'amount' => '12.345',
        ],
        'expected' => [
            'amount' => 'The amount field must have 0-2 decimal places.',
        ],
    ],
    'gt:0' => [
        'data' => [
            'rounding_increment' => 0,
        ],
        'expected' => [
            'rounding_increment' => 'The rounding increment field must be greater than 0.',
        ],
    ],
    'gt:minimum_value' => [
        'data' => [
            'minimum_value' => 10,
            'maximum_value' => 10,
        ],
        'expected' => [
            'maximum_value' => 'The maximum value field must be greater than 10.',
        ],
    ],
    'gte:0' => [
        'data' => [
            'minimum_value' => -1,
        ],
        'expected' => [
            'minimum_value' => 'The minimum value field must be greater than or equal to 0.',
        ],
    ],
    'gte:minimum_value' => [
        'data' => [
            'minimum_value' => 5,
            'maximum_value' => 4,
        ],
        'expected' => [
            'maximum_value' => 'The maximum value field must be greater than or equal to 5.',
        ],
    ],
    'integer' => [
        'data' => [
            'minimum_value' => 'not-an-integer',
            'maximum_value' => 'not-an-integer',
        ],
        'expected' => [
            'minimum_value' => 'The minimum value field must be an integer.',
            'maximum_value' => 'The maximum value field must be an integer.',
        ],
    ],
    'lte:maximum_value' => [
        'data' => [
            'minimum_value' => 5,
            'maximum_value' => 4,
        ],
        'expected' => [
            'minimum_value' => 'The minimum value field must be less than or equal to 4.',
        ],
    ],
]
```

Keep `gt` and `gte` separate. When a request declares both reciprocal rules,
the deliberately reversed pair may produce both messages:

```php
[
    'paired strict range' => [
        'data' => [
            'minimum_value' => 11,
            'maximum_value' => 10,
        ],
        'expected' => [
            'minimum_value' => 'The minimum value field must be less than or equal to 10.',
            'maximum_value' => 'The maximum value field must be greater than 11.',
        ],
    ],
]
```

The baseline catalogs preserve `numeric` and `decimal:0,4`. Do not replace
either with the cases above: type, precision, and boundary failures are
different contracts.

## Related References

- [`../dataset-catalog.md`](../dataset-catalog.md)
- [`../../actions/update/range-open-ended.md`](../../actions/update/range-open-ended.md)
- [`../../actions/update/stored-bound-validation.md`](../../actions/update/stored-bound-validation.md)
