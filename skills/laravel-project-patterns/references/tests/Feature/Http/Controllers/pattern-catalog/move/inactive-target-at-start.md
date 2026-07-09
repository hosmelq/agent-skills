# Inactive Move Target At Start

## When To Use

Use this leaf for a distinct accepted inactive-target and nullable move-after path.

## Pattern

```php
it('allows moving an inactive record', function (): void {
    $workspace = Workspace::factory()->createOne();
    $parentRecord = ParentRecord::factory()
        ->for($workspace)
        ->inactive()
        ->createOne();

    login(workspace: $workspace);

    mock(MoveParentRecord::class)
        ->shouldReceive('handle')
        ->once()
        ->withArgs(fn (
            ParentRecord $parentRecordArgument,
            null $moveAfterParentRecordArgument
        ): bool => $parentRecordArgument->is($parentRecord)
            && $moveAfterParentRecordArgument === null);

    $response = patch(route('workspaces.parent-records.move', [
        'workspace' => $workspace,
        'parent_record' => $parentRecord,
    ]), [
        'move_after_id' => null,
    ]);

    $response->assertRedirect()
        ->assertToast('Parent record moved');
});
```

## Related References

- [Parent router](../move.md)
