# Four-Resource Destroy Binding Boundaries: Leaf Boundaries

## When To Use

Read this leaf when leaf boundaries for `Four-Resource Route Chain` is in scope.

## Pattern

```php
describe('destroy', function (): void {
    it('returns not found when leaf record belongs to another child record', function (): void {
        $childRecord = ChildRecord::factory()->createOne();
        $leafRecord = LeafRecord::factory()
            ->recycle($childRecord->parentRecord->workspace)
            ->createOne();

        login(workspace: $childRecord->parentRecord->workspace);

        $response = delete(route('workspaces.parent-records.children.leaves.destroy', [
            'workspace' => $childRecord->parentRecord->workspace,
            'parent_record' => $childRecord->parentRecord,
            'child_record' => $childRecord,
            'leaf_record' => $leafRecord,
        ]));

        $response->assertNotFound();
    });

    it('returns not found when leaf record belongs to another Workspace', function (): void {
        $childRecord = ChildRecord::factory()->createOne();
        $leafRecord = LeafRecord::factory()->createOne();

        login(workspace: $childRecord->parentRecord->workspace);

        $response = delete(route('workspaces.parent-records.children.leaves.destroy', [
            'workspace' => $childRecord->parentRecord->workspace,
            'parent_record' => $childRecord->parentRecord,
            'child_record' => $childRecord,
            'leaf_record' => $leafRecord,
        ]));

        $response->assertNotFound();
    });

    it('returns not found when leaf record is soft deleted', function (): void {
        $leafRecord = LeafRecord::factory()->trashed()->createOne();

        login(workspace: $leafRecord->childRecord->parentRecord->workspace);

        $response = delete(route('workspaces.parent-records.children.leaves.destroy', [
            'workspace' => $leafRecord->childRecord->parentRecord->workspace,
            'parent_record' => $leafRecord->childRecord->parentRecord,
            'child_record' => $leafRecord->childRecord,
            'leaf_record' => $leafRecord,
        ]));

        $response->assertNotFound();
    });
});
```

## Related References

- [`../binding-boundaries.md`](../binding-boundaries.md)
