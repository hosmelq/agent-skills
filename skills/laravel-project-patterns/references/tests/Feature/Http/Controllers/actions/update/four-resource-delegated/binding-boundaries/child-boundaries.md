# Four-Resource Update Binding Boundaries: Child Boundaries

## When To Use

Read this leaf when child boundaries for `Four-Resource Route Chain` is in scope.

## Pattern

```php
describe('update', function (): void {
    it('returns not found when child record belongs to another parent record', function (): void {
        $parentRecord = ParentRecord::factory()->createOne();
        $leafRecord = LeafRecord::factory()
            ->for(ChildRecord::factory()->recycle($parentRecord->workspace))
            ->createOne();

        login(workspace: $parentRecord->workspace);

        $response = patch(route('workspaces.parent-records.children.leaves.update', [
            'workspace' => $parentRecord->workspace,
            'parent_record' => $parentRecord,
            'child_record' => $leafRecord->childRecord,
            'leaf_record' => $leafRecord,
        ]));

        $response->assertNotFound();
    });

    it('returns not found when child record belongs to another Workspace', function (): void {
        $parentRecord = ParentRecord::factory()->createOne();
        $leafRecord = LeafRecord::factory()->createOne();

        login(workspace: $parentRecord->workspace);

        $response = patch(route('workspaces.parent-records.children.leaves.update', [
            'workspace' => $parentRecord->workspace,
            'parent_record' => $parentRecord,
            'child_record' => $leafRecord->childRecord,
            'leaf_record' => $leafRecord,
        ]));

        $response->assertNotFound();
    });

    it('returns not found when child record is soft deleted', function (): void {
        $childRecord = ChildRecord::factory()->trashed()->createOne();
        $leafRecord = LeafRecord::factory()
            ->for($childRecord)
            ->createOne();

        login(workspace: $childRecord->parentRecord->workspace);

        $response = patch(route('workspaces.parent-records.children.leaves.update', [
            'workspace' => $childRecord->parentRecord->workspace,
            'parent_record' => $childRecord->parentRecord,
            'child_record' => $childRecord,
            'leaf_record' => $leafRecord,
        ]));

        $response->assertNotFound();
    });
});
```

## Related References

- [`../binding-boundaries.md`](../binding-boundaries.md)
