# Two-Resource Show Tests

## When To Use

Read this reference for a two-resource `show` route or when auditing the matching nested binding depth.

## Pattern

### Two-Resource Route Chain (`workspaces.parent-records.show`)

```php
describe('show', function (): void {
    it('requires authentication', function (): void {
        $parentRecord = ParentRecord::factory()->createOne();

        $response = get(route('workspaces.parent-records.show', [
            'workspace' => $parentRecord->workspace,
            'parent_record' => $parentRecord,
        ]));

        $response->assertRedirectToRoute('login');
    });

    it('prevents viewing from an unrelated Workspace', function (): void {
        $parentRecord = ParentRecord::factory()->createOne();

        login();

        $response = get(route('workspaces.parent-records.show', [
            'workspace' => $parentRecord->workspace,
            'parent_record' => $parentRecord,
        ]));

        $response->assertForbidden();
    });

    it('returns not found when parent record belongs to another Workspace', function (): void {
        $workspace = Workspace::factory()->createOne();
        $parentRecord = ParentRecord::factory()->createOne();

        login(workspace: $workspace);

        $response = get(route('workspaces.parent-records.show', [
            'workspace' => $workspace,
            'parent_record' => $parentRecord,
        ]));

        $response->assertNotFound();
    });

    it('returns not found when parent record is soft deleted', function (): void {
        $parentRecord = ParentRecord::factory()->trashed()->createOne();

        login(workspace: $parentRecord->workspace);

        $response = get(route('workspaces.parent-records.show', [
            'workspace' => $parentRecord->workspace,
            'parent_record' => $parentRecord,
        ]));

        $response->assertNotFound();
    });

    it('shows a parent record', function (): void {
        $parentRecord = ParentRecord::factory()->createOne();

        login(workspace: $parentRecord->workspace);

        $response = get(route('workspaces.parent-records.show', [
            'workspace' => $parentRecord->workspace,
            'parent_record' => $parentRecord,
        ]));

        $response->assertOk()
            ->assertInertia(function (AssertableInertia $page) use ($parentRecord): void {
                $page->component('parent-records/Show')
                    ->where('workspace.id', $parentRecord->workspace->public_id)
                    ->where('parentRecord.id', $parentRecord->public_id);
            });
    });
});
```

## Related References

- [`../show.md`](../show.md)
