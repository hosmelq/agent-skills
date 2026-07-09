# Nested Binding Patterns

## When To Use

Read this focused reference when the task involves nested binding patterns.

## Pattern

### Nested Binding Patterns

- `Workspace`-scoped model belongs to the route `Workspace`.
- Direct child belongs to the route parent.
- Deep leaf belongs to the middle parent, the middle parent belongs to the outer parent, and the full chain belongs to the route `Workspace`.
- Same-`Workspace` wrong-parent graphs must be `404` or excluded.
- Cross-`Workspace` children must be `404` even when their public IDs are valid.
- Redundant ownership mismatch must be `404` or excluded when a child stores both direct-parent and `Workspace`/ancestor ownership.
- Trashed route models should be `404` unless the route explicitly includes them.

When a leaf stores a redundant `workspace_id`, intentionally create an
inconsistent row to prove the member binding rejects it even though its direct
parent matches:

```php
it('returns not found when leaf record ownership does not match its parent graph', function (): void {
    $childRecord = ChildRecord::factory()->createOne();
    $otherWorkspace = Workspace::factory()->createOne();
    $leafRecord = LeafRecord::factory()
        ->for($childRecord)
        ->createOne([
            'workspace_id' => $otherWorkspace->id,
        ]);

    login(workspace: $childRecord->parentRecord->workspace);

    $response = get(route('workspaces.parent-records.children.leaves.show', [
        'workspace' => $childRecord->parentRecord->workspace,
        'parent_record' => $childRecord->parentRecord,
        'child_record' => $childRecord,
        'leaf_record' => $leafRecord,
    ]));

    $response->assertNotFound();
});
```

The corresponding index must exclude the same inconsistent graph:

```php
it('does not include leaf records with mismatched redundant ownership', function (): void {
    $leafRecord = LeafRecord::factory()->createOne();
    $otherWorkspace = Workspace::factory()->createOne();
    $mismatchedLeafRecord = LeafRecord::factory()
        ->for($leafRecord->childRecord)
        ->createOne([
            'workspace_id' => $otherWorkspace->id,
        ]);

    login(workspace: $leafRecord->childRecord->parentRecord->workspace);

    $response = get(route('workspaces.parent-records.children.leaves.index', [
        'workspace' => $leafRecord->childRecord->parentRecord->workspace,
        'parent_record' => $leafRecord->childRecord->parentRecord,
        'child_record' => $leafRecord->childRecord,
    ]));

    $response->assertOk()
        ->assertInertia(function (AssertableInertia $page) use ($leafRecord, $mismatchedLeafRecord): void {
            $page->has('leafRecords.data', 1, function (AssertableJson $json) use ($leafRecord, $mismatchedLeafRecord): void {
                $json
                    ->where('id', $leafRecord->public_id)
                    ->whereNot('id', $mismatchedLeafRecord->public_id)
                    ->etc();
            });
        });
});
```

Use this pair only when the live schema stores that redundant owner and the
route/list query is responsible for enforcing it.

## Related References

- [`../pattern-catalog.md`](../pattern-catalog.md)
