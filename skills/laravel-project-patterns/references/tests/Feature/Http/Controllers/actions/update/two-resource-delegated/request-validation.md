# Two-Resource Delegated Update Tests: Request Validation

## When To Use

Read this leaf when request validation for `Two-Resource Route Chain` is in scope.

## Pattern

```php
describe('update', function (): void {
    it('validates fields', function (array $data, array $expected): void {
        $parentRecord = ParentRecord::factory()->createOne();

        login(workspace: $parentRecord->workspace);

        $response = patch(route('workspaces.parent-records.update', [
            'workspace' => $parentRecord->workspace,
            'parent_record' => $parentRecord,
        ]), $data);

        $response->assertRedirectBackWithErrors($expected);
    })->with([
        'enum' => [
            'data' => [
                'example_mode' => 'invalid',
            ],
            'expected' => [
                'example_mode' => 'The selected example mode is invalid.',
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
        'sometimes (required)' => [
            'data' => [
                'example_mode' => '',
                'name' => '',
            ],
            'expected' => [
                'example_mode' => 'The example mode field is required.',
                'name' => 'The name field is required.',
            ],
        ],
    ]);
});
```

## Related References

- [`../two-resource-delegated.md`](../two-resource-delegated.md)
