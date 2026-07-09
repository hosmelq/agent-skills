# Four-Resource Destroy Lifecycle and Success

## When To Use

Read this reference when the task requires four-resource destroy lifecycle and success.

## Pattern

### Four-Resource Route Chain (`workspaces.parent-records.children.leaves.destroy`)

```php
describe('destroy', function (): void {
    it('prevents deleting when the parent record is inactive', function (): void {
        $leafRecord = LeafRecord::factory()
            ->for(ChildRecord::factory()->for(ParentRecord::factory()->inactive()))
            ->createOne();

        login(workspace: $leafRecord->childRecord->parentRecord->workspace);

        $response = delete(route('workspaces.parent-records.children.leaves.destroy', [
            'workspace' => $leafRecord->childRecord->parentRecord->workspace,
            'parent_record' => $leafRecord->childRecord->parentRecord,
            'child_record' => $leafRecord->childRecord,
            'leaf_record' => $leafRecord,
        ]));

        $response->assertForbidden();
    });

    it('deletes a leaf record', function (): void {
        $leafRecord = LeafRecord::factory()->createOne();

        login(workspace: $leafRecord->childRecord->parentRecord->workspace);

        mock(DeleteLeafRecord::class)
            ->shouldReceive('handle')
            ->once()
            ->withArgs(fn (
                LeafRecord $leafRecordArgument
            ): bool => $leafRecordArgument->is($leafRecord));

        $response = delete(route('workspaces.parent-records.children.leaves.destroy', [
            'workspace' => $leafRecord->childRecord->parentRecord->workspace,
            'parent_record' => $leafRecord->childRecord->parentRecord,
            'child_record' => $leafRecord->childRecord,
            'leaf_record' => $leafRecord,
        ]));

        $response->assertRedirectToRoute('workspaces.parent-records.children.leaves.index', [
            'workspace' => $leafRecord->childRecord->parentRecord->workspace,
            'parent_record' => $leafRecord->childRecord->parentRecord,
            'child_record' => $leafRecord->childRecord,
        ])
            ->assertToast('Leaf record deleted');
    });
});
```

## Related References

- [`../four-resource.md`](../four-resource.md)
