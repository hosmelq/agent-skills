# Four-Resource Store Binding Boundaries: Child Boundaries

## When To Use

Read this leaf when child boundaries for `Four-Resource Route Chain` is in scope.

## Pattern

```php
describe('store', function (): void {
    it('returns not found when child record belongs to another parent record', function (): void {
        $parentRecord = ParentRecord::factory()->createOne();
        $childRecord = ChildRecord::factory()
            ->recycle($parentRecord->workspace)
            ->createOne();

        login(workspace: $parentRecord->workspace);

        $response = post(route('workspaces.parent-records.children.leaves.store', [
            'workspace' => $parentRecord->workspace,
            'parent_record' => $parentRecord,
            'child_record' => $childRecord,
        ]));

        $response->assertNotFound();
    });

    it('returns not found when child record belongs to another Workspace', function (): void {
        $parentRecord = ParentRecord::factory()->createOne();
        $childRecord = ChildRecord::factory()->createOne();

        login(workspace: $parentRecord->workspace);

        $response = post(route('workspaces.parent-records.children.leaves.store', [
            'workspace' => $parentRecord->workspace,
            'parent_record' => $parentRecord,
            'child_record' => $childRecord,
        ]));

        $response->assertNotFound();
    });

    it('returns not found when child record is soft deleted', function (): void {
        $childRecord = ChildRecord::factory()->trashed()->createOne();

        login(workspace: $childRecord->parentRecord->workspace);

        $response = post(route('workspaces.parent-records.children.leaves.store', [
            'workspace' => $childRecord->parentRecord->workspace,
            'parent_record' => $childRecord->parentRecord,
            'child_record' => $childRecord,
        ]));

        $response->assertNotFound();
    });
});
```

## Related References

- [`../binding-boundaries.md`](../binding-boundaries.md)
