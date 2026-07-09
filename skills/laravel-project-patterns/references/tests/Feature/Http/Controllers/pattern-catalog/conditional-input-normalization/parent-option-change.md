# Parent Option Change Normalization

## When To Use

Use this leaf when a parent selection changes dependent normalized input.

## Pattern

```php
it('clears the dependent option when its parent option changes', function (): void {
    $parentRecord = ParentRecord::factory()->createOne([
        'parent_option' => ParentOption::First,
        'dependent_option' => 'existing',
    ]);

    login(workspace: $parentRecord->workspace);

    mock(UpdateParentRecord::class)
        ->shouldReceive('handle')
        ->once()
        ->withArgs(fn (
            ParentRecord $parentRecordArgument,
            UpdateParentRecordInput $input
        ): bool => $parentRecordArgument->is($parentRecord)
            && $input->parentOption === ParentOption::Second
            && $input->dependentOption === null);

    $response = patch(route('workspaces.parent-records.update', [
        'workspace' => $parentRecord->workspace,
        'parent_record' => $parentRecord,
    ]), [
        'parent_option' => ParentOption::Second->value,
    ]);

    $response->assertRedirectToRoute('workspaces.parent-records.show', [
        'workspace' => $parentRecord->workspace,
        'parent_record' => $parentRecord,
    ]);
});
```

## Related References

- [Parent router](../conditional-input-normalization.md)
