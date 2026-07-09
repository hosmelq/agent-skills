# Move-After Group Boundary

## When To Use

Use this leaf when an owner-valid move-after record must also belong to the
target's ordered group.

## Pattern

```php
it('returns not found for a move-after record from another group', function (): void {
    $workspace = Workspace::factory()->createOne();
    $parentRecord = ParentRecord::factory()
        ->for($workspace)
        ->firstGroup()
        ->createOne();
    $moveAfterParentRecord = ParentRecord::factory()
        ->for($workspace)
        ->secondGroup()
        ->createOne();

    login(workspace: $workspace);

    $response = patch(route('workspaces.parent-records.move', [
        'workspace' => $workspace,
        'parent_record' => $parentRecord,
    ]), [
        'move_after_id' => $moveAfterParentRecord->public_id,
    ]);

    $response->assertNotFound();
});
```

## Related References

- [Parent router](../move.md)
