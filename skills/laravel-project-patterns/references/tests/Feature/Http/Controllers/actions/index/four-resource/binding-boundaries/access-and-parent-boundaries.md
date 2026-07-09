# Four-Resource Index Binding Boundaries: Access And Parent Boundaries

## When To Use

Read this leaf when access and parent boundaries for `Four-Resource Route Chain` is in scope.

## Pattern

```php
describe('index', function (): void {
    it('requires authentication', function (): void {
        $childRecord = ChildRecord::factory()->createOne();

        $response = get(route('workspaces.parent-records.children.leaves.index', [
            'workspace' => $childRecord->parentRecord->workspace,
            'parent_record' => $childRecord->parentRecord,
            'child_record' => $childRecord,
        ]));

        $response->assertRedirectToRoute('login');
    });

    it('prevents listing from an unrelated Workspace', function (): void {
        $childRecord = ChildRecord::factory()->createOne();

        login();

        $response = get(route('workspaces.parent-records.children.leaves.index', [
            'workspace' => $childRecord->parentRecord->workspace,
            'parent_record' => $childRecord->parentRecord,
            'child_record' => $childRecord,
        ]));

        $response->assertForbidden();
    });

    it('returns not found when parent record belongs to another Workspace', function (): void {
        $workspace = Workspace::factory()->createOne();
        $childRecord = ChildRecord::factory()->createOne();

        login(workspace: $workspace);

        $response = get(route('workspaces.parent-records.children.leaves.index', [
            'workspace' => $workspace,
            'parent_record' => $childRecord->parentRecord,
            'child_record' => $childRecord,
        ]));

        $response->assertNotFound();
    });

    it('returns not found when parent record is soft deleted', function (): void {
        $parentRecord = ParentRecord::factory()->trashed()->createOne();
        $childRecord = ChildRecord::factory()
            ->for($parentRecord)
            ->createOne();

        login(workspace: $parentRecord->workspace);

        $response = get(route('workspaces.parent-records.children.leaves.index', [
            'workspace' => $parentRecord->workspace,
            'parent_record' => $parentRecord,
            'child_record' => $childRecord,
        ]));

        $response->assertNotFound();
    });
});
```

## Related References

- [`../binding-boundaries.md`](../binding-boundaries.md)
