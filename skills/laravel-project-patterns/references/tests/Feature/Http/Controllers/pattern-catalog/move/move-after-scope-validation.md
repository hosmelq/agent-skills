# Move-After Scope Validation

## When To Use

Use when the request's owner-scoped `exists` rule excludes cross-owner and
soft-deleted move-after records.

## Pattern

```php
it('rejects a move-after record from another workspace', function (): void {
    $parentRecord = ParentRecord::factory()->createOne();
    $moveAfterParentRecord = ParentRecord::factory()->createOne();

    login(workspace: $parentRecord->workspace);

    $response = patch(route('workspaces.parent-records.move', [
        'workspace' => $parentRecord->workspace,
        'parent_record' => $parentRecord,
    ]), [
        'move_after_id' => $moveAfterParentRecord->public_id,
    ]);

    $response->assertRedirectBackWithErrors([
        'move_after_id' => 'The selected move after id is invalid.',
    ]);
});

it('rejects a soft-deleted move-after record', function (): void {
    $parentRecord = ParentRecord::factory()->createOne();
    $moveAfterParentRecord = ParentRecord::factory()
        ->for($parentRecord->workspace)
        ->trashed()
        ->createOne();

    login(workspace: $parentRecord->workspace);

    $response = patch(route('workspaces.parent-records.move', [
        'workspace' => $parentRecord->workspace,
        'parent_record' => $parentRecord,
    ]), [
        'move_after_id' => $moveAfterParentRecord->public_id,
    ]);

    $response->assertRedirectBackWithErrors([
        'move_after_id' => 'The selected move after id is invalid.',
    ]);
});
```

## Related References

- [Parent router](../move.md)
- [`references/app/Http/Requests/patterns/move-within-group.md`](../../../../../../app/Http/Requests/patterns/move-within-group.md)
