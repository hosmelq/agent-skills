# Invokable Default Selection

## When To Use

Use this leaf for an invokable default-selection controller test.

## Pattern

```php
it('sets a child record as default', function (): void {
    $childRecord = ChildRecord::factory()->createOne();

    login(workspace: $childRecord->parentRecord->workspace);

    mock(SetDefaultChildRecord::class)
        ->shouldReceive('handle')
        ->once()
        ->withArgs(fn (
            ChildRecord $childRecordArgument
        ): bool => $childRecordArgument->is($childRecord));

    $response = patch(route('workspaces.parent-records.children.make-default', [
        'workspace' => $childRecord->parentRecord->workspace,
        'parent_record' => $childRecord->parentRecord,
        'child_record' => $childRecord,
    ]));

    $response->assertRedirectToRoute('workspaces.parent-records.children.index', [
        'workspace' => $childRecord->parentRecord->workspace,
        'parent_record' => $childRecord->parentRecord,
    ])
        ->assertToast('Default child record updated');
});
```

## Related References

- [Parent router](../focused-variant-examples.md)
