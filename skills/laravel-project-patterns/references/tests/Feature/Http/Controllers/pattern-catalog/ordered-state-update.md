# Ordered State Update Boundary

## When To Use

Use when a partial HTTP update accepts a grouping field and delegates the
ordered transition to an action.

## Pattern

The controller test proves the distinct accepted input path, enum mapping,
target identity, redirect, and toast. It does not reproduce the action's
persistence matrix:

```php
it('maps the grouping field to the delegated update action', function (): void {
    $parentRecord = ParentRecord::factory()->firstGroup()->createOne();

    login(workspace: $parentRecord->workspace);

    mock(UpdateParentRecord::class)
        ->shouldReceive('handle')
        ->once()
        ->withArgs(fn (
            ParentRecord $parentRecordArgument,
            UpdateParentRecordInput $input
        ): bool => $parentRecordArgument->is($parentRecord)
            && $input->category === RecordCategory::Second);

    $response = patch(route('workspaces.parent-records.update', [
        'workspace' => $parentRecord->workspace,
        'parent_record' => $parentRecord,
    ]), [
        'category' => RecordCategory::Second->value,
    ]);

    $response->assertRedirectToRoute('workspaces.parent-records.show', [
        'workspace' => $parentRecord->workspace,
        'parent_record' => $parentRecord,
    ])
        ->assertToast('Parent record updated');
});
```

The action integration suite separately proves all three unique persistence
contracts, in this order:

1. moving into a populated destination appends the target and normalizes the
   source group;
2. moving into an empty destination persists order `1`;
3. omitting the grouping field leaves the original order unchanged.

Use database assertions for the moved record's ordinary persisted fields.
Extract ordered IDs and use `expect(...)` for the Eloquent ordering behavior.

## Related References

- [`../pattern-catalog.md`](../pattern-catalog.md)
- [`references/app/Actions/patterns/group-order-transitions.md`](../../../../../app/Actions/patterns/group-order-transitions.md)
- [`references/tests/Integration/Actions/scenario-catalog/state-and-order-actions.md`](../../../../Integration/Actions/scenario-catalog/state-and-order-actions.md)
