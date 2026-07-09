# Two-Resource Index Tests

## When To Use

Read this reference for a two-resource `index` route or when auditing the matching nested binding depth.

## Pattern

### Two-Resource Route Chain (`workspaces.parent-records.index`)

```php
describe('index', function (): void {
    it('requires authentication', function (): void {
        $workspace = Workspace::factory()->createOne();

        $response = get(route('workspaces.parent-records.index', [
            'workspace' => $workspace,
        ]));

        $response->assertRedirectToRoute('login');
    });

    it('prevents listing from an unrelated Workspace', function (): void {
        $workspace = Workspace::factory()->createOne();

        login();

        $response = get(route('workspaces.parent-records.index', [
            'workspace' => $workspace,
        ]));

        $response->assertForbidden();
    });

    it('lists parent records', function (): void {
        $parentRecord = ParentRecord::factory()->createOne();

        login(workspace: $parentRecord->workspace);

        $response = get(route('workspaces.parent-records.index', [
            'workspace' => $parentRecord->workspace,
        ]));

        $response->assertOk()
            ->assertInertia(function (AssertableInertia $page) use ($parentRecord): void {
                $page->component('parent-records/Index')
                    ->where('workspace.id', $parentRecord->workspace->public_id)
                    ->where('parentRecords.data.0.id', $parentRecord->public_id);
            });
    });

    it('does not include parent records from other Workspaces', function (): void {
        $parentRecord = ParentRecord::factory()->createOne();
        $otherParentRecord = ParentRecord::factory()->createOne();

        login(workspace: $parentRecord->workspace);

        $response = get(route('workspaces.parent-records.index', [
            'workspace' => $parentRecord->workspace,
        ]));

        $response->assertOk()
            ->assertInertia(function (AssertableInertia $page) use ($parentRecord, $otherParentRecord): void {
                $page->component('parent-records/Index')
                    ->has('parentRecords.data', 1, function (AssertableJson $json) use ($parentRecord, $otherParentRecord): void {
                        $json
                            ->where('id', $parentRecord->public_id)
                            ->whereNot('id', $otherParentRecord->public_id)
                            ->etc();
                    });
            });
    });
});
```

## Related References

- [`../index.md`](../index.md)
