# Three-Resource Destroy Tests: Delegated Success

## When To Use

Read this leaf when delegated success for `Three-Resource Route Chain` is in scope.

## Pattern

```php
describe('destroy', function (): void {
    it('deletes a child record', function (): void {
        $childRecord = ChildRecord::factory()->createOne();

        login(workspace: $childRecord->parentRecord->workspace);

        mock(DeleteChildRecord::class)
            ->shouldReceive('handle')
            ->once()
            ->withArgs(fn (
                ChildRecord $childRecordArgument
            ): bool => $childRecordArgument->is($childRecord));

        $response = delete(route('workspaces.parent-records.children.destroy', [
            'workspace' => $childRecord->parentRecord->workspace,
            'parent_record' => $childRecord->parentRecord,
            'child_record' => $childRecord,
        ]));

        $response->assertRedirectToRoute('workspaces.parent-records.show', [
            'workspace' => $childRecord->parentRecord->workspace,
            'parent_record' => $childRecord->parentRecord,
        ])
            ->assertToast('Child record deleted');
    });
});
```

## Related References

- [`../three-resource.md`](../three-resource.md)
