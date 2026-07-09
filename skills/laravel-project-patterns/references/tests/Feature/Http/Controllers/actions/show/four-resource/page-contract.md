# Four-Resource Show Page Contract

## When To Use

Read this reference when the task requires four-resource show page contract.

## Pattern

### Four-Resource Route Chain (`workspaces.parent-records.children.leaves.show`)

```php
describe('show', function (): void {
    it('shows a leaf record', function (): void {
        $leafRecord = LeafRecord::factory()->createOne();

        login(workspace: $leafRecord->childRecord->parentRecord->workspace);

        $response = get(route('workspaces.parent-records.children.leaves.show', [
            'workspace' => $leafRecord->childRecord->parentRecord->workspace,
            'parent_record' => $leafRecord->childRecord->parentRecord,
            'child_record' => $leafRecord->childRecord,
            'leaf_record' => $leafRecord,
        ]));

        $response->assertOk()
            ->assertInertia(function (AssertableInertia $page) use ($leafRecord): void {
                $page->component('leaves/Show')
                    ->where('workspace.id', $leafRecord->childRecord->parentRecord->workspace->public_id)
                    ->where('parentRecord.id', $leafRecord->childRecord->parentRecord->public_id)
                    ->where('childRecord.id', $leafRecord->childRecord->public_id)
                    ->where('leafRecord.id', $leafRecord->public_id);
            });
    });

    it('shows a leaf record when the parent record is inactive if read continuity is allowed', function (): void {
        $leafRecord = LeafRecord::factory()
            ->for(ChildRecord::factory()->for(ParentRecord::factory()->inactive()))
            ->createOne();

        login(workspace: $leafRecord->childRecord->parentRecord->workspace);

        $response = get(route('workspaces.parent-records.children.leaves.show', [
            'workspace' => $leafRecord->childRecord->parentRecord->workspace,
            'parent_record' => $leafRecord->childRecord->parentRecord,
            'child_record' => $leafRecord->childRecord,
            'leaf_record' => $leafRecord,
        ]));

        $response->assertOk()
            ->assertInertia(function (AssertableInertia $page) use ($leafRecord): void {
                $page->component('leaves/Show')
                    ->where('leafRecord.id', $leafRecord->public_id)
                    ->where('parentRecord.inactive_at', $leafRecord->childRecord->parentRecord->inactive_at->toJSON());
            });
    });
});
```

## Related References

- [`../four-resource.md`](../four-resource.md)
