# Update Paired And Conditional Validation

## When To Use

Use this leaf for partial-update datasets with nullable paired rules.

## Pattern

### Update Example

```php
it('validates fields', function (array $data, array $expected): void {
    $parentRecord = ParentRecord::factory()->createOne();

    login(workspace: $parentRecord->workspace);

    $response = patch(route('workspaces.parent-records.update', [
        'workspace' => $parentRecord->workspace,
        'parent_record' => $parentRecord,
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
    'present_with:end_value' => [
        'data' => [
            'end_value' => null,
        ],
        'expected' => [
            'start_value' => 'The start value field must be present when end value is present.',
        ],
    ],
    'present_with:start_value' => [
        'data' => [
            'start_value' => null,
        ],
        'expected' => [
            'end_value' => 'The end value field must be present when start value is present.',
        ],
    ],
    'required_if:example_mode' => [
        'data' => [
            'example_mode' => 'advanced',
        ],
        'expected' => [
            'conditional_value' => 'The conditional value field is required.',
        ],
    ],
    'required_with:end_value' => [
        'data' => [
            'end_value' => 1,
        ],
        'expected' => [
            'start_value' => 'The start value field is required when end value is present.',
        ],
    ],
    'required_with:start_value' => [
        'data' => [
            'start_value' => 1,
        ],
        'expected' => [
            'end_value' => 'The end value field is required when start value is present.',
        ],
    ],
]);
```

## Related References

- [Parent router](../required-with-and-array.md)
