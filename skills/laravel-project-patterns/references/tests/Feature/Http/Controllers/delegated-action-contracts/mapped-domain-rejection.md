# Mapped Action Domain Rejection

## When To Use

Use this leaf for exception-to-validation mapping through a mocked action.

## Pattern

### Mapped Action Domain Rejection Example

```php
it('rejects updating a child record when its parent is inactive', function (): void {
    $childRecord = ChildRecord::factory()->createOne();

    login(workspace: $childRecord->parentRecord->workspace);

    mock(UpdateChildRecord::class)
        ->shouldReceive('handle')
        ->once()
        ->withArgs(fn (
            ChildRecord $childRecordArgument,
            ExampleInput $input
        ): bool => $childRecordArgument->is($childRecord)
            && $input->name === 'Updated')
        ->andThrow(CannotUpdateChildRecord::becauseParentIsInactive());

    $response = patch(route('workspaces.parent-records.children.update', [
        'workspace' => $childRecord->parentRecord->workspace,
        'parent_record' => $childRecord->parentRecord,
        'child_record' => $childRecord,
    ]), [
        'name' => 'Updated',
    ]);

    $response->assertRedirectBackWithErrors([
        'parent_record' => 'The selected parent record is not active.',
    ]);
});
```

This proves only the HTTP exception-to-validation mapping. The action
integration test proves the guard and persisted result, plus transaction or
lock behavior only when the live action already owns it.

## Related References

- [Parent router](../delegated-action-contracts.md)
