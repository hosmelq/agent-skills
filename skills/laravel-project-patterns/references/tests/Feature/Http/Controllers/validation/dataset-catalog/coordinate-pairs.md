# Coordinate Pair Dataset Cases

## When To Use

Use when the request accepts optional paired coordinates. Prove each outer
boundary and both directions of `required_with` and `present_with`.

## Pattern

```php
[
    'between:-180,180' => [
        'data' => [
            'longitude' => 181,
        ],
        'expected' => [
            'longitude' => 'The longitude field must be between -180 and 180.',
        ],
    ],
    'between:-180,180 (minimum)' => [
        'data' => [
            'longitude' => -181,
        ],
        'expected' => [
            'longitude' => 'The longitude field must be between -180 and 180.',
        ],
    ],
    'between:-90,90' => [
        'data' => [
            'latitude' => 91,
        ],
        'expected' => [
            'latitude' => 'The latitude field must be between -90 and 90.',
        ],
    ],
    'between:-90,90 (minimum)' => [
        'data' => [
            'latitude' => -91,
        ],
        'expected' => [
            'latitude' => 'The latitude field must be between -90 and 90.',
        ],
    ],
    'present_with:latitude' => [
        'data' => [
            'latitude' => 12.1,
        ],
        'expected' => [
            'longitude' => 'The longitude field must be present when latitude is present.',
        ],
    ],
    'present_with:longitude' => [
        'data' => [
            'longitude' => -86.2,
        ],
        'expected' => [
            'latitude' => 'The latitude field must be present when longitude is present.',
        ],
    ],
    'required_with:latitude' => [
        'data' => [
            'latitude' => 12.1,
            'longitude' => '',
        ],
        'expected' => [
            'longitude' => 'The longitude field is required when latitude is present.',
        ],
    ],
    'required_with:longitude' => [
        'data' => [
            'latitude' => '',
            'longitude' => -86.2,
        ],
        'expected' => [
            'latitude' => 'The latitude field is required when longitude is present.',
        ],
    ],
]
```

The generic start/end pair in `required-with-and-array.md` is the same rule
shape but does not replace the four distinct coordinate boundaries above.

## Related References

- [`../dataset-catalog.md`](../dataset-catalog.md)
- [`../required-with-and-array.md`](../required-with-and-array.md)
