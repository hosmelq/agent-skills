# Four-Resource Index Listing Contract: Listing And Read Continuity

## When To Use

Read this leaf when listing and read continuity is in scope for Four-Resource Route Chain (`workspaces.parent-records.children.leaves.index`).

## Pattern

```php
describe('index', function (): void {
    it('lists leaf records from the child record', function (): void {
        $leafRecord = LeafRecord::factory()->createOne();

        login(workspace: $leafRecord->childRecord->parentRecord->workspace);

        $response = get(route('workspaces.parent-records.children.leaves.index', [
            'workspace' => $leafRecord->childRecord->parentRecord->workspace,
            'parent_record' => $leafRecord->childRecord->parentRecord,
            'child_record' => $leafRecord->childRecord,
        ]));

        $response->assertOk()
            ->assertInertia(function (AssertableInertia $page) use ($leafRecord): void {
                $page->component('leaves/Index')
                    ->where('workspace.id', $leafRecord->childRecord->parentRecord->workspace->public_id)
                    ->where('parentRecord.id', $leafRecord->childRecord->parentRecord->public_id)
                    ->where('childRecord.id', $leafRecord->childRecord->public_id)
                    ->where('leafRecords.data.0.id', $leafRecord->public_id);
            });
    });

    it('lists leaf records when the parent record is inactive if read continuity is allowed', function (): void {
        $leafRecord = LeafRecord::factory()
            ->for(ChildRecord::factory()->for(ParentRecord::factory()->inactive()))
            ->createOne();

        login(workspace: $leafRecord->childRecord->parentRecord->workspace);

        $response = get(route('workspaces.parent-records.children.leaves.index', [
            'workspace' => $leafRecord->childRecord->parentRecord->workspace,
            'parent_record' => $leafRecord->childRecord->parentRecord,
            'child_record' => $leafRecord->childRecord,
        ]));

        $response->assertOk()
            ->assertInertia(function (AssertableInertia $page) use ($leafRecord): void {
                $page->component('leaves/Index')
                    ->where('leafRecords.data.0.id', $leafRecord->public_id)
                    ->where('parentRecord.inactive_at', $leafRecord->childRecord->parentRecord->inactive_at->toJSON());
            });
    });
});
```

## Related References

- [`../listing-contract.md`](../listing-contract.md)
