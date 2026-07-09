# Move To Group Start

## When To Use

Use for the distinct accepted `null` move-after path on an active target.

## Pattern

```php
it('moves a record to the start of its group', function (): void {
    $parentRecord = ParentRecord::factory()->createOne();

    login(workspace: $parentRecord->workspace);

    mock(MoveParentRecord::class)
        ->shouldReceive('handle')
        ->once()
        ->withArgs(fn (
            ParentRecord $parentRecordArgument,
            null $moveAfterParentRecordArgument
        ): bool => $parentRecordArgument->is($parentRecord)
            && $moveAfterParentRecordArgument === null);

    $response = patch(route('workspaces.parent-records.move', [
        'workspace' => $parentRecord->workspace,
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
