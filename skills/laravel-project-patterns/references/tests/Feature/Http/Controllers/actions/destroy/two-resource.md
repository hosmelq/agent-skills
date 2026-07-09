# Two-Resource Destroy Tests

## When To Use

Read this reference for a two-resource `destroy` route or when auditing the matching nested binding depth.

## Pattern

### Two-Resource Route Chain (`workspaces.parent-records.destroy`)

```php
describe('destroy', function (): void {
    it('requires authentication', function (): void {
        $parentRecord = ParentRecord::factory()->createOne();

        $response = delete(route('workspaces.parent-records.destroy', [
            'workspace' => $parentRecord->workspace,
            'parent_record' => $parentRecord,
        ]));

        $response->assertRedirectToRoute('login');
    });

    it('prevents deleting from an unrelated Workspace', function (): void {
        $parentRecord = ParentRecord::factory()->createOne();

        login();

        $response = delete(route('workspaces.parent-records.destroy', [
            'workspace' => $parentRecord->workspace,
            'parent_record' => $parentRecord,
        ]));

        $response->assertForbidden();
    });

    it('returns not found when parent record belongs to another Workspace', function (): void {
        $workspace = Workspace::factory()->createOne();
        $parentRecord = ParentRecord::factory()->createOne();

        login(workspace: $workspace);

        $response = delete(route('workspaces.parent-records.destroy', [
            'workspace' => $workspace,
            'parent_record' => $parentRecord,
        ]));

        $response->assertNotFound();
    });

    it('returns not found when parent record is soft deleted', function (): void {
        $parentRecord = ParentRecord::factory()->trashed()->createOne();

        login(workspace: $parentRecord->workspace);

        $response = delete(route('workspaces.parent-records.destroy', [
            'workspace' => $parentRecord->workspace,
            'parent_record' => $parentRecord,
        ]));

        $response->assertNotFound();
    });

    it('deletes a parent record', function (): void {
        $parentRecord = ParentRecord::factory()->createOne();

        login(workspace: $parentRecord->workspace);

        mock(DeleteParentRecord::class)
            ->shouldReceive('handle')
            ->once()
            ->withArgs(fn (
                ParentRecord $parentRecordArgument
            ): bool => $parentRecordArgument->is($parentRecord));

        $response = delete(route('workspaces.parent-records.destroy', [
            'workspace' => $parentRecord->workspace,
            'parent_record' => $parentRecord,
        ]));

        $response->assertRedirectToRoute('workspaces.parent-records.index', [
            'workspace' => $parentRecord->workspace,
        ])
            ->assertToast('Parent record deleted');
    });
});
```

## Related References

- [`../destroy.md`](../destroy.md)
