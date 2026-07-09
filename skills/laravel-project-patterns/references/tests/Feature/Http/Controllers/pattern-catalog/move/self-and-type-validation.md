# Move Self And Type Validation

## When To Use

Use when the target is route-bound and `move_after_id` is the only request
field.

## Pattern

```php
it('rejects moving a record after itself', function (): void {
    $parentRecord = ParentRecord::factory()->createOne();

    login(workspace: $parentRecord->workspace);

    $response = patch(route('workspaces.parent-records.move', [
        'workspace' => $parentRecord->workspace,
        'parent_record' => $parentRecord,
    ]), [
        'move_after_id' => $parentRecord->public_id,
    ]);

    $response->assertRedirectBackWithErrors([
        'move_after_id' => 'The selected move after id is invalid.',
    ]);
});

it('validates move-after fields', function (mixed $value, string $message): void {
    $parentRecord = ParentRecord::factory()->createOne();

    login(workspace: $parentRecord->workspace);

    $response = patch(route('workspaces.parent-records.move', [
        'workspace' => $parentRecord->workspace,
        'parent_record' => $parentRecord,
    ]), [
        'move_after_id' => $value,
    ]);

    $response->assertRedirectBackWithErrors([
        'move_after_id' => $message,
    ]);
})->with([
    'string' => [
        'value' => 123,
        'message' => 'The move after id field must be a string.',
    ],
]);
```

The self-move case is a named `Rule::notIn(...)` contract, not a
`different:record_id` dataset case.

## Related References

- [Parent router](../move.md)
- [`references/app/Http/Requests/patterns/move-within-group.md`](../../../../../../app/Http/Requests/patterns/move-within-group.md)
