# Tests

## When To Use

Read this focused reference when the task involves tests.

## Pattern

### Tests

- Web controller tests live under `tests/Feature/Http/Controllers`.
- API controller tests live under `tests/Feature/Http/Controllers/Api`.
- Load the path-specific controller test references before writing or changing controller tests.
- When a mutation controller delegates persistence or domain side effects to a Data input-backed action, feature tests should mock that action and assert the controller passes the expected bound model/current actor plus a typed input.
- Assert only selected input properties that prove the controller boundary: input class, route-bound model identity, current actor when relevant, submitted values for the field under test, and `Optional` only when omission is the test's purpose.
- Do not compare every input property in one matcher unless that feature case
  uniquely owns the complete Request-to-Input mapping. Field validation and
  action persistence tests do not justify unrelated matcher fields.
- Leave persistence, defaults, nullable clearing, and action-owned side effects to action integration tests.
- If the controller catches a domain exception from a delegated action and maps it to a validation error, add a controller feature test that mocks the action to throw that exception and asserts the validation error. Keep the ordinary policy/form-request lifecycle guard test separate when that guard should stop before the action is called.
- When the mocked action returns a model for a redirect, return a persisted factory model. Do not set generated route keys such as `public_id`, `slug`, or generated codes unless the literal value is asserted.
- Configure `andReturn(...)` only when the controller consumes the action result. Update, delete, lifecycle, and other bound-model controllers that ignore the return value should not invent one in the mock.
- Do not populate unrelated attributes such as labels, codes, contact values, or phone numbers unless the controller assertion uses them.
- Do not put Pest `expect()` chains inside Mockery `withArgs(...)` or `andReturnUsing(...)` argument matching. Use boolean conditions in mock matchers and keep rich `expect()` assertions outside mocks.
- Keep at least one success feature case for every distinct HTTP input contract,
  plus each unique authentication, binding, authorization, validation,
  exception-mapping, redirect, toast, and response branch that applies. An
  action test cannot replace those entrypoint contracts.

Example action mock for a top-level update:

```php
mock(UpdateParentRecord::class)
    ->shouldReceive('handle')
    ->once()
    ->withArgs(
        fn (ParentRecord $parentRecordArgument, UpdateParentRecordInput $input): bool =>
            $parentRecordArgument->is($parentRecord)
            && $input->name === 'Updated name'
    );
```

Example action mock for a child update:

```php
mock(UpdateChildRecord::class)
    ->shouldReceive('handle')
    ->once()
    ->withArgs(fn (
        ChildRecord $childRecordArgument,
        UpdateChildRecordInput $input
    ): bool => $childRecordArgument->is($childRecord)
        && $input->name === 'Updated name');
```

Example delegated domain rejection test:

```php
it('rejects updating a child record when its parent is inactive', function (): void {
    $childRecord = ChildRecord::factory()->createOne();

    login(workspace: $childRecord->parentRecord->workspace);

    mock(UpdateChildRecord::class)
        ->shouldReceive('handle')
        ->once()
        ->withArgs(fn (
            ChildRecord $childRecordArgument,
            UpdateChildRecordInput $input
        ): bool => $childRecordArgument->is($childRecord)
            && $input->name === 'Updated name')
        ->andThrow(CannotUpdateChildRecord::becauseParentIsInactive());

    $response = patch(route('workspaces.parent-records.children.update', [
        'workspace' => $childRecord->parentRecord->workspace,
        'parent_record' => $childRecord->parentRecord,
        'child_record' => $childRecord,
    ]), [
        'name' => 'Updated name',
    ]);

    $response->assertRedirectBackWithErrors([
        'parent_record' => 'The parent record is inactive.',
    ]);
});
```

Compare nested web controllers against existing nested tests before finalizing. Parent mismatch, parent soft-deleted, leaf direct-parent mismatch, leaf `Workspace` mismatch, and leaf soft-deleted cases are separate cases when the resource shape supports them.

## Related References

- [`../README.md`](../README.md)
