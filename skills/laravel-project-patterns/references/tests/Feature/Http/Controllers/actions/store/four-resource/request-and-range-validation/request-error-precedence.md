# Four-Resource Store Request and Range Validation: Request Error Precedence

## When To Use

Read this leaf when request error precedence is in scope for Four-Resource Route Chain (`workspaces.parent-records.children.leaves.store`).

## Pattern

```php
describe('store', function (): void {
    it('does not evaluate action-owned range guards when request validation fails', function (): void {
        $childRecord = ChildRecord::factory()->createOne();

        login(workspace: $childRecord->parentRecord->workspace);

        mock(CreateLeafRecord::class)
            ->shouldNotReceive('handle');

        $response = post(route('workspaces.parent-records.children.leaves.store', [
            'workspace' => $childRecord->parentRecord->workspace,
            'parent_record' => $childRecord->parentRecord,
            'child_record' => $childRecord,
        ]), [
            'minimum_value' => '1',
            'maximum_value' => '3',
        ]);

        $response->assertRedirectBackWithErrors([
            'name' => 'The name field is required.',
        ])
            ->assertSessionDoesntHaveErrors(['minimum_value']);
    });
});
```

## Related References

- [`../request-and-range-validation.md`](../request-and-range-validation.md)
