# Four-Resource Create Tests: Lifecycle And Success

## When To Use

Read this leaf when lifecycle and success for `Four-Resource Route Chain` is in scope.

## Pattern

```php
describe('create', function (): void {
    it('prevents viewing when the parent record is inactive', function (): void {
        $childRecord = ChildRecord::factory()
            ->for(ParentRecord::factory()->inactive())
            ->createOne();

        login(workspace: $childRecord->parentRecord->workspace);

        $response = get(route('workspaces.parent-records.children.leaves.create', [
            'workspace' => $childRecord->parentRecord->workspace,
            'parent_record' => $childRecord->parentRecord,
            'child_record' => $childRecord,
        ]));

        $response->assertForbidden();
    });

    it('shows the create leaf record page', function (): void {
        $childRecord = ChildRecord::factory()->createOne();

        login(workspace: $childRecord->parentRecord->workspace);

        $response = get(route('workspaces.parent-records.children.leaves.create', [
            'workspace' => $childRecord->parentRecord->workspace,
            'parent_record' => $childRecord->parentRecord,
            'child_record' => $childRecord,
        ]));

        $response->assertOk()
            ->assertInertia(function (AssertableInertia $page) use ($childRecord): void {
                $page->component('leaves/Create')
                    ->where('workspace.id', $childRecord->parentRecord->workspace->public_id)
                    ->where('parentRecord.id', $childRecord->parentRecord->public_id)
                    ->where('childRecord.id', $childRecord->public_id);
            });
    });
});
```

## Related References

- [`../four-resource.md`](../four-resource.md)
