# Four-Resource Update Request and Action Boundary

## When To Use

Read this reference when the task requires four-resource update request and action boundary.

## Pattern

### Four-Resource Route Chain (`workspaces.parent-records.children.leaves.update`)

```php
describe('update', function (): void {
    it('does not call the action when request validation fails', function (): void {
        $leafRecord = LeafRecord::factory()->createOne();

        login(workspace: $leafRecord->childRecord->parentRecord->workspace);

        mock(UpdateLeafRecord::class)
            ->shouldNotReceive('handle');

        $response = patch(route('workspaces.parent-records.children.leaves.update', [
            'workspace' => $leafRecord->childRecord->parentRecord->workspace,
            'parent_record' => $leafRecord->childRecord->parentRecord,
            'child_record' => $leafRecord->childRecord,
            'leaf_record' => $leafRecord,
        ]), [
            'name' => '',
        ]);

        $response->assertRedirectBackWithErrors([
            'name' => 'The name field is required.',
        ]);
    });

    it('prevents updating when dependent records exist', function (): void {
        $leafRecord = LeafRecord::factory()->createOne();

        login(workspace: $leafRecord->childRecord->parentRecord->workspace);

        mock(UpdateLeafRecord::class)
            ->shouldReceive('handle')
            ->once()
            ->withArgs(fn (
                LeafRecord $leafRecordArgument,
                ExampleInput $input
            ): bool => $leafRecordArgument->is($leafRecord)
                && $input->name === 'Updated Leaf')
            ->andThrow(CannotUpdateLeafRecord::becauseDependentRecordsExist());

        $response = patch(route('workspaces.parent-records.children.leaves.update', [
            'workspace' => $leafRecord->childRecord->parentRecord->workspace,
            'parent_record' => $leafRecord->childRecord->parentRecord,
            'child_record' => $leafRecord->childRecord,
            'leaf_record' => $leafRecord,
        ]), [
            'name' => 'Updated Leaf',
        ]);

        $response->assertRedirectBackWithErrors([
            'leaf_record' => 'The leaf record cannot be changed while dependent records exist.',
        ]);
    });

    it('passes partial input to the delegated action', function (): void {
        $leafRecord = LeafRecord::factory()->createOne();

        login(workspace: $leafRecord->childRecord->parentRecord->workspace);

        mock(UpdateLeafRecord::class)
            ->shouldReceive('handle')
            ->once()
            ->withArgs(fn (
                LeafRecord $leafRecordArgument,
                ExampleInput $input
            ): bool => $leafRecordArgument->is($leafRecord)
                && $input->name === 'Updated Leaf');

        $response = patch(route('workspaces.parent-records.children.leaves.update', [
            'workspace' => $leafRecord->childRecord->parentRecord->workspace,
            'parent_record' => $leafRecord->childRecord->parentRecord,
            'child_record' => $leafRecord->childRecord,
            'leaf_record' => $leafRecord,
        ]), [
            'name' => 'Updated Leaf',
        ]);

        $response->assertRedirectToRoute('workspaces.parent-records.children.leaves.show', [
            'workspace' => $leafRecord->childRecord->parentRecord->workspace,
            'parent_record' => $leafRecord->childRecord->parentRecord,
            'child_record' => $leafRecord->childRecord,
            'leaf_record' => $leafRecord,
        ])
            ->assertToast('Leaf record updated');
    });
});
```

## Related References

- [`../four-resource-delegated.md`](../four-resource-delegated.md)
