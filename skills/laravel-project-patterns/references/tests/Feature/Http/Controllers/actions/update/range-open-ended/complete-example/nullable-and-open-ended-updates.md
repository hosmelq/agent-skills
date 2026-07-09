# Complete Range And Open-Ended Update Example: Nullable And Open-Ended Updates

## When To Use

Read this leaf when nullable and open-ended updates is in scope for Complete Range And Open-Ended Update Example.

## Pattern

```php
it('allows clearing the maximum value', function (): void {
    $leafRecord = LeafRecord::factory()
        ->forRange(2, 10)
        ->createOne();

    login(workspace: $leafRecord->childRecord->parentRecord->workspace);

    mock(UpdateLeafRecord::class)
        ->shouldReceive('handle')
        ->once()
        ->withArgs(fn (
            LeafRecord $leafRecordArgument,
            UpdateLeafRecordInput $input
        ): bool => $leafRecordArgument->is($leafRecord)
            && $input->maximumValue === null);

    $response = patch(route('workspaces.parent-records.children.leaves.update', [
        'workspace' => $leafRecord->childRecord->parentRecord->workspace,
        'parent_record' => $leafRecord->childRecord->parentRecord,
        'child_record' => $leafRecord->childRecord,
        'leaf_record' => $leafRecord,
    ]), [
        'maximum_value' => null,
    ]);

    $response->assertRedirectToRoute('workspaces.parent-records.children.leaves.show', [
        'workspace' => $leafRecord->childRecord->parentRecord->workspace,
        'parent_record' => $leafRecord->childRecord->parentRecord,
        'child_record' => $leafRecord->childRecord,
        'leaf_record' => $leafRecord,
    ])
        ->assertToast('Leaf record updated');
});

it('allows minimum value updates when the stored maximum value is open ended', function (): void {
    $leafRecord = LeafRecord::factory()
        ->forRange(5, null)
        ->createOne();

    login(workspace: $leafRecord->childRecord->parentRecord->workspace);

    mock(UpdateLeafRecord::class)
        ->shouldReceive('handle')
        ->once()
        ->withArgs(fn (
            LeafRecord $leafRecordArgument,
            UpdateLeafRecordInput $input
        ): bool => $leafRecordArgument->is($leafRecord)
            && $input->maximumValue instanceof Optional
            && $input->minimumValue === '11');

    $response = patch(route('workspaces.parent-records.children.leaves.update', [
        'workspace' => $leafRecord->childRecord->parentRecord->workspace,
        'parent_record' => $leafRecord->childRecord->parentRecord,
        'child_record' => $leafRecord->childRecord,
        'leaf_record' => $leafRecord,
    ]), [
        'minimum_value' => 11,
    ]);

    $response->assertRedirectToRoute('workspaces.parent-records.children.leaves.show', [
        'workspace' => $leafRecord->childRecord->parentRecord->workspace,
        'parent_record' => $leafRecord->childRecord->parentRecord,
        'child_record' => $leafRecord->childRecord,
        'leaf_record' => $leafRecord,
    ])
        ->assertToast('Leaf record updated');
});
```

## Related References

- [`../complete-example.md`](../complete-example.md)
