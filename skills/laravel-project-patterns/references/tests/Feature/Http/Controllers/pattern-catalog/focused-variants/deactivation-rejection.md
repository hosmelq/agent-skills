# Deactivation Rejection

## When To Use

Use this leaf for a deactivation rejection mapped to the HTTP response.

## Pattern

```php
it('prevents deactivating a parent record with dependent records', function (): void {
    $parentRecord = ParentRecord::factory()->createOne();

    login(workspace: $parentRecord->workspace);

    mock(DeactivateParentRecord::class)
        ->shouldReceive('handle')
        ->once()
        ->withArgs(fn (ParentRecord $parentRecordArgument): bool => $parentRecordArgument->is($parentRecord))
        ->andThrow(CannotDeactivateParentRecord::becauseDependentRecordsExist());

    $response = post(route('workspaces.parent-records.deactivation.store', [
        'workspace' => $parentRecord->workspace,
        'parent_record' => $parentRecord,
    ]));

    $response->assertRedirectBackWithErrors([
        'parent_record' => 'This record cannot be deactivated while dependent records exist.',
    ]);
});
```

## Related References

- [Parent router](../focused-variant-examples.md)
