# Delegated Destroy Domain Rejection

## When To Use

Read this reference when a delegated destroy action can reject deletion for distinct dependency families.

## Pattern

### Delegated Destroy Domain Rejection

```php
it('prevents deleting when related records exist', function (): void {
    $parentRecord = ParentRecord::factory()->createOne();

    login(workspace: $parentRecord->workspace);

    mock(DeleteParentRecord::class)
        ->shouldReceive('handle')
        ->once()
        ->withArgs(fn (
            ParentRecord $parentRecordArgument
        ): bool => $parentRecordArgument->is($parentRecord))
        ->andThrow(CannotDeleteParentRecord::becauseRelatedRecordsExist());

    $response = delete(route('workspaces.parent-records.destroy', [
        'workspace' => $parentRecord->workspace,
        'parent_record' => $parentRecord,
    ]));

    $response->assertRedirectBackWithErrors([
        'parent_record' => 'The parent record cannot be deleted while related records exist.',
    ]);
});
```

Keep separate controller tests when dependency families change route or bound-
model state, middleware, authorization, request validation, action reachability,
exception branches, validation fields, messages, statuses, redirects, toasts,
or side effects. For new coverage, active and soft-deleted dependencies may
share one mapping case only after every one of those HTTP inputs and outcomes
has been proven identical; the action integration suite still proves both
dependency states.

Do not use this rule to delete an existing controller case. Deletion requires
an explicit full-equivalence audit plus a named surviving test for the same
request and bound-model state. Action coverage is not evidence because it
bypasses the entry point. Do not create dependency fixtures merely to label a
branch that the mocked action cannot observe.

The action scenario catalog preserves the distinct active, inactive, and
soft-deleted dependency cases:

- [`references/tests/Integration/Actions/scenario-catalog/lifecycle-default-and-delete-actions.md`](../../../../../Integration/Actions/scenario-catalog/lifecycle-default-and-delete-actions.md)

## Related References

- [`../destroy.md`](../destroy.md)
