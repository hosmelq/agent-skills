# Invokable Exclusive Selection

## When To Use

Use when an invokable or singleton mutation selects one active eligible record
inside a `Workspace` and maps action eligibility failures to validation.

## Pattern

Keep this order:

1. requires authentication;
2. prevents selection from an unrelated `Workspace`;
3. returns `404` for a target from another `Workspace`;
4. returns `404` for a soft-deleted target;
5. maps each distinct eligibility exception to its public validation error;
6. selects the active eligible target.

For new coverage, inactive and ineligible targets may share one mapping test
only after a full-equivalence audit proves identical route binding, middleware,
authorization, request validation, action reachability, exception factory,
field, message, status, redirect, toast, and side effects:

```php
it('rejects selecting an ineligible record', function (): void {
    $parentRecord = ParentRecord::factory()
        ->ineligible()
        ->createOne();

    login(workspace: $parentRecord->workspace);

    mock(SelectParentRecord::class)
        ->shouldReceive('handle')
        ->once()
        ->withArgs(fn (
            ParentRecord $parentRecordArgument
        ): bool => $parentRecordArgument->is($parentRecord))
        ->andThrow(CannotSelectParentRecord::becauseItIsNotActiveAndEligible());

    $response = post(route('workspaces.parent-records.selection.store', [
        'workspace' => $parentRecord->workspace,
        'parent_record' => $parentRecord,
    ]));

    $response->assertRedirectBackWithErrors([
        'parent_record' => 'Only active eligible records can be selected.',
    ]);
});
```

Keep separate controller cases when any HTTP input or outcome differs. Do not
delete an existing case merely because both states reach the same action
exception; deletion requires naming a surviving controller test that proves
the same bound-model state and complete HTTP contract. The action integration
suite still preserves inactive and ineligible state as separate guard cases.

For success, assert redirect/toast plus action invocation at the HTTP boundary.
The action integration suite proves that the former selection clears and the
target becomes the only selection.

## Related References

- [`../pattern-catalog.md`](../pattern-catalog.md)
- [`focused-variant-examples.md`](focused-variant-examples.md)
- [`references/tests/Integration/Actions/scenario-catalog/state-and-order-actions.md`](../../../../Integration/Actions/scenario-catalog/state-and-order-actions.md)
