# Three-Resource Store Tests: Access And Binding

## When To Use

Read this leaf when access and binding for `Three-Resource Route Chain` is in scope.

## Pattern

```php
describe('store', function (): void {
    it('requires authentication', function (): void {
        $parentRecord = ParentRecord::factory()->createOne();

        $response = post(route('workspaces.parent-records.children.store', [
            'workspace' => $parentRecord->workspace,
            'parent_record' => $parentRecord,
        ]));

        $response->assertRedirectToRoute('login');
    });

    it('prevents creating from an unrelated Workspace', function (): void {
        $parentRecord = ParentRecord::factory()->createOne();

        login();

        $response = post(route('workspaces.parent-records.children.store', [
            'workspace' => $parentRecord->workspace,
            'parent_record' => $parentRecord,
        ]));

        $response->assertForbidden();
    });

    it('returns not found when parent record belongs to another Workspace', function (): void {
        $workspace = Workspace::factory()->createOne();
        $parentRecord = ParentRecord::factory()->createOne();

        login(workspace: $workspace);

        $response = post(route('workspaces.parent-records.children.store', [
            'workspace' => $workspace,
            'parent_record' => $parentRecord,
        ]));

        $response->assertNotFound();
    });

    it('returns not found when parent record is soft deleted', function (): void {
        $parentRecord = ParentRecord::factory()->trashed()->createOne();

        login(workspace: $parentRecord->workspace);

        $response = post(route('workspaces.parent-records.children.store', [
            'workspace' => $parentRecord->workspace,
            'parent_record' => $parentRecord,
        ]));

        $response->assertNotFound();
    });
});
```

## Related References

- [`../three-resource.md`](../three-resource.md)
