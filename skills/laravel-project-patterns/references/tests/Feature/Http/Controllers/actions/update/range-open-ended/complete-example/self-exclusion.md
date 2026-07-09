# Complete Range And Open-Ended Update Example: Self Exclusion

## When To Use

Read this leaf when self exclusion is in scope for Complete Range And Open-Ended Update Example.

## Pattern

```php
it('excludes the updated record from overlap validation', function (): void {
    $leafRecord = LeafRecord::factory()
        ->forRange(0, 2)
        ->createOne();

    login(workspace: $leafRecord->childRecord->parentRecord->workspace);

    mock(UpdateLeafRecord::class)
        ->shouldReceive('handle')
        ->once()
        ->withArgs(fn (
            LeafRecord $leafRecordArgument,
            UpdateLeafRecordInput $input
        ): bool => $leafRecordArgument->is($leafRecord)
            && $input->name === 'Updated without range conflict');

    $response = patch(route('workspaces.parent-records.children.leaves.update', [
        'workspace' => $leafRecord->childRecord->parentRecord->workspace,
        'parent_record' => $leafRecord->childRecord->parentRecord,
        'child_record' => $leafRecord->childRecord,
        'leaf_record' => $leafRecord,
    ]), [
        'name' => 'Updated without range conflict',
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
