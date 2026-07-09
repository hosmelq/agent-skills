# Blank Stored-Value Fallback

## When To Use

Use this leaf when blank submitted input falls back to a stored value.

## Pattern

### Merge the Stored Value When a Field Is Blank

```php
it('updates when parent option is empty but matches the existing stored value', function (): void {
    $childRecord = ChildRecord::factory()->createOne([
        'parent_option_code' => 'AA',
    ]);

    login(workspace: $childRecord->parentRecord->workspace);

    mock(UpdateChildRecord::class)
        ->shouldReceive('handle')
        ->once()
        ->withArgs(fn (
            ChildRecord $childRecordArgument,
            UpdateChildRecordInput $input
        ): bool => $childRecordArgument->is($childRecord)
            && $input->childOptionCode === 'BB'
            && $input->parentOptionCode === 'AA');

    $response = patch(route('workspaces.parent-records.children.update', [
        'workspace' => $childRecord->parentRecord->workspace,
        'parent_record' => $childRecord->parentRecord,
        'child_record' => $childRecord,
    ]), [
        'parent_option_code' => '',
        'child_option_code' => 'BB',
    ]);

    $response->assertRedirectToRoute('workspaces.parent-records.children.show', [
        'workspace' => $childRecord->parentRecord->workspace,
        'parent_record' => $childRecord->parentRecord,
        'child_record' => $childRecord,
    ])
        ->assertToast('Child record updated');
});
```

## Related References

- [Parent router](../stored-and-normalized-values.md)
