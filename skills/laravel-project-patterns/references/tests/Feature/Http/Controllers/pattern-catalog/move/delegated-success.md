# Delegated Move Success

## When To Use

Use this leaf for the primary mocked move HTTP success.

## Pattern

```php
it('moves a record after another record within its group', function (): void {
    $workspace = Workspace::factory()->createOne();
    $firstParentRecord = ParentRecord::factory()
        ->for($workspace)
        ->firstGroup()
        ->createOne();
    $secondParentRecord = ParentRecord::factory()
        ->for($workspace)
        ->firstGroup()
        ->createOne();

    login(workspace: $workspace);

    mock(MoveParentRecord::class)
        ->shouldReceive('handle')
        ->once()
        ->withArgs(fn (
            ParentRecord $parentRecordArgument,
            ParentRecord $moveAfterParentRecordArgument
        ): bool => $parentRecordArgument->is($firstParentRecord)
            && $moveAfterParentRecordArgument->is($secondParentRecord));

    $response = patch(route('workspaces.parent-records.move', [
        'workspace' => $workspace,
        'parent_record' => $firstParentRecord,
    ]), [
        'move_after_id' => $secondParentRecord->public_id,
    ]);

    $response->assertRedirect()
        ->assertToast('Parent record moved');
});
```

## Related References

- [Parent router](../move.md)
