# Store Paired And Conditional Validation

## When To Use

Use this leaf for store datasets with paired, array, and conditional rules.

## Pattern

### Store Example

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
