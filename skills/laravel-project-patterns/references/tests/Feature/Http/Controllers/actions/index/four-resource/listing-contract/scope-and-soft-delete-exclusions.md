# Four-Resource Index Listing Contract: Scope And Soft-Delete Exclusions

## When To Use

Read this leaf when scope and soft-delete exclusions is in scope for Four-Resource Route Chain (`workspaces.parent-records.children.leaves.index`).

## Pattern

```php
describe('index', function (): void {
    it('does not include leaf records from another child record', function (): void {
        $leafRecord = LeafRecord::factory()->createOne();
        $otherLeafRecord = LeafRecord::factory()
            ->recycle($leafRecord->childRecord->parentRecord->workspace)
            ->createOne();

        login(workspace: $leafRecord->childRecord->parentRecord->workspace);

        $response = get(route('workspaces.parent-records.children.leaves.index', [
            'workspace' => $leafRecord->childRecord->parentRecord->workspace,
            'parent_record' => $leafRecord->childRecord->parentRecord,
            'child_record' => $leafRecord->childRecord,
        ]));

        $response->assertOk()
            ->assertInertia(function (AssertableInertia $page) use ($leafRecord, $otherLeafRecord): void {
                $page->component('leaves/Index')
                    ->has('leafRecords.data', 1, function (AssertableJson $json) use ($leafRecord, $otherLeafRecord): void {
                        $json
                            ->where('id', $leafRecord->public_id)
                            ->whereNot('id', $otherLeafRecord->public_id)
                            ->etc();
                    });
            });
    });

    it('does not include leaf records from another Workspace', function (): void {
        $leafRecord = LeafRecord::factory()->createOne();
        $otherLeafRecord = LeafRecord::factory()->createOne();

        login(workspace: $leafRecord->childRecord->parentRecord->workspace);

        $response = get(route('workspaces.parent-records.children.leaves.index', [
            'workspace' => $leafRecord->childRecord->parentRecord->workspace,
            'parent_record' => $leafRecord->childRecord->parentRecord,
            'child_record' => $leafRecord->childRecord,
        ]));

        $response->assertOk()
            ->assertInertia(function (AssertableInertia $page) use ($leafRecord, $otherLeafRecord): void {
                $page->component('leaves/Index')
                    ->has('leafRecords.data', 1, function (AssertableJson $json) use ($leafRecord, $otherLeafRecord): void {
                        $json
                            ->where('id', $leafRecord->public_id)
                            ->whereNot('id', $otherLeafRecord->public_id)
                            ->etc();
                    });
            });
    });

    it('does not include soft deleted leaf records', function (): void {
        $leafRecord = LeafRecord::factory()->createOne();
        $softDeletedLeafRecord = LeafRecord::factory()
            ->for($leafRecord->childRecord)
            ->trashed()
            ->createOne();

        login(workspace: $leafRecord->childRecord->parentRecord->workspace);

        $response = get(route('workspaces.parent-records.children.leaves.index', [
            'workspace' => $leafRecord->childRecord->parentRecord->workspace,
            'parent_record' => $leafRecord->childRecord->parentRecord,
            'child_record' => $leafRecord->childRecord,
        ]));

        $response->assertOk()
            ->assertInertia(function (AssertableInertia $page) use ($leafRecord, $softDeletedLeafRecord): void {
                $page->component('leaves/Index')
                    ->has('leafRecords.data', 1, function (AssertableJson $json) use ($leafRecord, $softDeletedLeafRecord): void {
                        $json
                            ->where('id', $leafRecord->public_id)
                            ->whereNot('id', $softDeletedLeafRecord->public_id)
                            ->etc();
                    });
            });
    });
});
```

## Related References

- [`../listing-contract.md`](../listing-contract.md)
