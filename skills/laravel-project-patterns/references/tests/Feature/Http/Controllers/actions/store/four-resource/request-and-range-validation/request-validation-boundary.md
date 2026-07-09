# Four-Resource Store Request and Range Validation: Request Validation Boundary

## When To Use

Read this leaf when request validation boundary is in scope for Four-Resource Route Chain (`workspaces.parent-records.children.leaves.store`).

## Pattern

```php
describe('store', function (): void {
    it('does not call the action when request validation fails', function (): void {
        $childRecord = ChildRecord::factory()->createOne();

        login(workspace: $childRecord->parentRecord->workspace);

        mock(CreateLeafRecord::class)
            ->shouldNotReceive('handle');

        $response = post(route('workspaces.parent-records.children.leaves.store', [
            'workspace' => $childRecord->parentRecord->workspace,
            'parent_record' => $childRecord->parentRecord,
            'child_record' => $childRecord,
        ]), [
            'name' => '',
        ]);

        $response->assertRedirectBackWithErrors([
            'name' => 'The name field is required.',
        ]);
    });
});
```

## Related References

- [`../request-and-range-validation.md`](../request-and-range-validation.md)
