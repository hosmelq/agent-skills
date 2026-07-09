# Four-Resource Edit Lifecycle and Page Contract

## When To Use

Read this reference when the task requires four-resource edit lifecycle and page contract.

## Pattern

### Four-Resource Route Chain (`workspaces.parent-records.children.leaves.edit`)

```php
describe('edit', function (): void {
    it('prevents viewing when the parent record is inactive', function (): void {
        $leafRecord = LeafRecord::factory()
            ->for(ChildRecord::factory()->for(ParentRecord::factory()->inactive()))
            ->createOne();

        login(workspace: $leafRecord->childRecord->parentRecord->workspace);

        $response = get(route('workspaces.parent-records.children.leaves.edit', [
            'workspace' => $leafRecord->childRecord->parentRecord->workspace,
            'parent_record' => $leafRecord->childRecord->parentRecord,
            'child_record' => $leafRecord->childRecord,
            'leaf_record' => $leafRecord,
        ]));

        $response->assertForbidden();
    });

    it('shows the edit leaf record page', function (): void {
        $leafRecord = LeafRecord::factory()->createOne();

        login(workspace: $leafRecord->childRecord->parentRecord->workspace);

        $response = get(route('workspaces.parent-records.children.leaves.edit', [
            'workspace' => $leafRecord->childRecord->parentRecord->workspace,
            'parent_record' => $leafRecord->childRecord->parentRecord,
            'child_record' => $leafRecord->childRecord,
            'leaf_record' => $leafRecord,
        ]));

        $response->assertOk()
            ->assertInertia(function (AssertableInertia $page) use ($leafRecord): void {
                $page->component('leaves/Edit')
                    ->where('workspace.id', $leafRecord->childRecord->parentRecord->workspace->public_id)
                    ->where('parentRecord.id', $leafRecord->childRecord->parentRecord->public_id)
                    ->where('childRecord.id', $leafRecord->childRecord->public_id)
                    ->where('leafRecord.id', $leafRecord->public_id);
            });
    });
});
```

## Related References

- [`../four-resource.md`](../four-resource.md)
