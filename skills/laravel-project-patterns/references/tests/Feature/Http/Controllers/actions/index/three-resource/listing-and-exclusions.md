# Three-Resource Index Tests: Listing And Exclusions

## When To Use

Read this leaf when listing and exclusions for `Three-Resource Route Chain` is in scope.

## Pattern

```php
describe('index', function (): void {
    it('lists child records from the parent record', function (): void {
        $childRecord = ChildRecord::factory()->createOne();

        login(workspace: $childRecord->parentRecord->workspace);

        $response = get(route('workspaces.parent-records.children.index', [
            'workspace' => $childRecord->parentRecord->workspace,
            'parent_record' => $childRecord->parentRecord,
        ]));

        $response->assertOk()
            ->assertInertia(function (AssertableInertia $page) use ($childRecord): void {
                $page->component('children/Index')
                    ->where('workspace.id', $childRecord->parentRecord->workspace->public_id)
                    ->where('parentRecord.id', $childRecord->parentRecord->public_id)
                    ->where('childRecords.data.0.id', $childRecord->public_id);
            });
    });

    it('does not include child records from another parent record', function (): void {
        $childRecord = ChildRecord::factory()->createOne();
        $otherChildRecord = ChildRecord::factory()
            ->recycle($childRecord->parentRecord->workspace)
            ->createOne();

        login(workspace: $childRecord->parentRecord->workspace);

        $response = get(route('workspaces.parent-records.children.index', [
            'workspace' => $childRecord->parentRecord->workspace,
            'parent_record' => $childRecord->parentRecord,
        ]));

        $response->assertOk()
            ->assertInertia(function (AssertableInertia $page) use ($childRecord, $otherChildRecord): void {
                $page->component('children/Index')
                    ->has('childRecords.data', 1, function (AssertableJson $json) use ($childRecord, $otherChildRecord): void {
                        $json
                            ->where('id', $childRecord->public_id)
                            ->whereNot('id', $otherChildRecord->public_id)
                            ->etc();
                    });
            });
    });

    it('does not include child records from another Workspace', function (): void {
        $childRecord = ChildRecord::factory()->createOne();
        $otherChildRecord = ChildRecord::factory()->createOne();

        login(workspace: $childRecord->parentRecord->workspace);

        $response = get(route('workspaces.parent-records.children.index', [
            'workspace' => $childRecord->parentRecord->workspace,
            'parent_record' => $childRecord->parentRecord,
        ]));

        $response->assertOk()
            ->assertInertia(function (AssertableInertia $page) use ($childRecord, $otherChildRecord): void {
                $page->component('children/Index')
                    ->has('childRecords.data', 1, function (AssertableJson $json) use ($childRecord, $otherChildRecord): void {
                        $json
                            ->where('id', $childRecord->public_id)
                            ->whereNot('id', $otherChildRecord->public_id)
                            ->etc();
                    });
            });
    });

    it('does not include soft deleted child records', function (): void {
        $childRecord = ChildRecord::factory()->createOne();
        $softDeletedChildRecord = ChildRecord::factory()
            ->for($childRecord->parentRecord)
            ->trashed()
            ->createOne();

        login(workspace: $childRecord->parentRecord->workspace);

        $response = get(route('workspaces.parent-records.children.index', [
            'workspace' => $childRecord->parentRecord->workspace,
            'parent_record' => $childRecord->parentRecord,
        ]));

        $response->assertOk()
            ->assertInertia(function (AssertableInertia $page) use ($childRecord, $softDeletedChildRecord): void {
                $page->component('children/Index')
                    ->has('childRecords.data', 1, function (AssertableJson $json) use ($childRecord, $softDeletedChildRecord): void {
                        $json
                            ->where('id', $childRecord->public_id)
                            ->whereNot('id', $softDeletedChildRecord->public_id)
                            ->etc();
                    });
            });
    });
});
```

## Related References

- [`../three-resource.md`](../three-resource.md)
