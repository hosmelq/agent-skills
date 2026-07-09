# Four-Resource Store Delegated Success

## When To Use

Read this reference when the task requires four-resource store delegated success.

## Pattern

### Four-Resource Route Chain (`workspaces.parent-records.children.leaves.store`)

```php
describe('store', function (): void {
    it('creates a leaf record through the delegated action', function (): void {
        $childRecord = ChildRecord::factory()->createOne();
        $leafRecord = LeafRecord::factory()
            ->for($childRecord)
            ->createOne();

        login(workspace: $childRecord->parentRecord->workspace);

        mock(CreateLeafRecord::class)
            ->shouldReceive('handle')
            ->once()
            ->withArgs(fn (
                ChildRecord $childRecordArgument,
                ExampleInput $input
            ): bool => $childRecordArgument->is($childRecord)
                && $input->name === 'Example Leaf')
            ->andReturn($leafRecord);

        $response = post(route('workspaces.parent-records.children.leaves.store', [
            'workspace' => $childRecord->parentRecord->workspace,
            'parent_record' => $childRecord->parentRecord,
            'child_record' => $childRecord,
        ]), [
            'name' => 'Example Leaf',
        ]);

        $response->assertRedirectToRoute('workspaces.parent-records.children.leaves.show', [
            'workspace' => $childRecord->parentRecord->workspace,
            'parent_record' => $childRecord->parentRecord,
            'child_record' => $childRecord,
            'leaf_record' => $leafRecord,
        ])
            ->assertToast('Leaf record created');
    });

    it('creates a leaf record with an open-ended maximum value', function (): void {
        $childRecord = ChildRecord::factory()->createOne();
        $leafRecord = LeafRecord::factory()
            ->for($childRecord)
            ->createOne();

        login(workspace: $childRecord->parentRecord->workspace);

        mock(CreateLeafRecord::class)
            ->shouldReceive('handle')
            ->once()
            ->withArgs(fn (
                ChildRecord $childRecordArgument,
                ExampleInput $input
            ): bool => $childRecordArgument->is($childRecord)
                && $input->maximumValue === null
                && $input->minimumValue === '0.5')
            ->andReturn($leafRecord);

        $response = post(route('workspaces.parent-records.children.leaves.store', [
            'workspace' => $childRecord->parentRecord->workspace,
            'parent_record' => $childRecord->parentRecord,
            'child_record' => $childRecord,
        ]), [
            'minimum_value' => '0.5',
            'maximum_value' => null,
            'name' => 'Open Ended Leaf',
        ]);

        $response->assertRedirectToRoute('workspaces.parent-records.children.leaves.show', [
            'workspace' => $childRecord->parentRecord->workspace,
            'parent_record' => $childRecord->parentRecord,
            'child_record' => $childRecord,
            'leaf_record' => $leafRecord,
        ])
            ->assertToast('Leaf record created');
    });
});
```

## Related References

- [`../four-resource.md`](../four-resource.md)
