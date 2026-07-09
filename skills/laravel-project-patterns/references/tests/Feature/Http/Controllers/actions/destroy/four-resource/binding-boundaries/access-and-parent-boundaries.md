# Four-Resource Destroy Binding Boundaries: Access And Parent Boundaries

## When To Use

Read this leaf when access and parent boundaries for `Four-Resource Route Chain` is in scope.

## Pattern

```php
describe('destroy', function (): void {
    it('requires authentication', function (): void {
        $leafRecord = LeafRecord::factory()->createOne();

        $response = delete(route('workspaces.parent-records.children.leaves.destroy', [
            'workspace' => $leafRecord->childRecord->parentRecord->workspace,
            'parent_record' => $leafRecord->childRecord->parentRecord,
            'child_record' => $leafRecord->childRecord,
            'leaf_record' => $leafRecord,
        ]));

        $response->assertRedirectToRoute('login');
    });

    it('prevents deleting from an unrelated Workspace', function (): void {
        $leafRecord = LeafRecord::factory()->createOne();

        login();

        $response = delete(route('workspaces.parent-records.children.leaves.destroy', [
            'workspace' => $leafRecord->childRecord->parentRecord->workspace,
            'parent_record' => $leafRecord->childRecord->parentRecord,
            'child_record' => $leafRecord->childRecord,
            'leaf_record' => $leafRecord,
        ]));

        $response->assertForbidden();
    });

    it('returns not found when parent record belongs to another Workspace', function (): void {
        $workspace = Workspace::factory()->createOne();
        $leafRecord = LeafRecord::factory()->createOne();

        login(workspace: $workspace);

        $response = delete(route('workspaces.parent-records.children.leaves.destroy', [
            'workspace' => $workspace,
            'parent_record' => $leafRecord->childRecord->parentRecord,
            'child_record' => $leafRecord->childRecord,
            'leaf_record' => $leafRecord,
        ]));

        $response->assertNotFound();
    });

    it('returns not found when parent record is soft deleted', function (): void {
        $parentRecord = ParentRecord::factory()->trashed()->createOne();
        $leafRecord = LeafRecord::factory()
            ->for(ChildRecord::factory()->for($parentRecord))
            ->createOne();

        login(workspace: $parentRecord->workspace);

        $response = delete(route('workspaces.parent-records.children.leaves.destroy', [
            'workspace' => $parentRecord->workspace,
            'parent_record' => $parentRecord,
            'child_record' => $leafRecord->childRecord,
            'leaf_record' => $leafRecord,
        ]));

        $response->assertNotFound();
    });
});
```

## Related References

- [`../binding-boundaries.md`](../binding-boundaries.md)
