# Four-Resource Store Request and Range Validation: Action Range Exceptions

## When To Use

Read this leaf when action range exceptions is in scope for Four-Resource Route Chain (`workspaces.parent-records.children.leaves.store`).

## Pattern

```php
describe('store', function (): void {
    it('rejects overlapping ranges', function (): void {
        $childRecord = ChildRecord::factory()->createOne();

        login(workspace: $childRecord->parentRecord->workspace);

        mock(CreateLeafRecord::class)
            ->shouldReceive('handle')
            ->once()
            ->withArgs(fn (
                ChildRecord $childRecordArgument,
                ExampleInput $input
            ): bool => $childRecordArgument->is($childRecord)
                && $input->maximumValue === '3'
                && $input->minimumValue === '1')
            ->andThrow(CannotCreateLeafRecord::becauseRangeOverlaps());

        $response = post(route('workspaces.parent-records.children.leaves.store', [
            'workspace' => $childRecord->parentRecord->workspace,
            'parent_record' => $childRecord->parentRecord,
            'child_record' => $childRecord,
        ]), [
            'minimum_value' => '1',
            'maximum_value' => '3',
            'name' => 'Example Leaf',
        ]);

        $response->assertRedirectBackWithErrors([
            'range' => 'The requested range overlaps an existing record.',
        ]);
    });

    it('rejects a second open-ended range', function (): void {
        $childRecord = ChildRecord::factory()->createOne();

        login(workspace: $childRecord->parentRecord->workspace);

        mock(CreateLeafRecord::class)
            ->shouldReceive('handle')
            ->once()
            ->withArgs(fn (
                ChildRecord $childRecordArgument,
                ExampleInput $input
            ): bool => $childRecordArgument->is($childRecord)
                && $input->maximumValue === null
                && $input->minimumValue === '10')
            ->andThrow(CannotCreateLeafRecord::becauseOpenEndedRangeAlreadyExists());

        $response = post(route('workspaces.parent-records.children.leaves.store', [
            'workspace' => $childRecord->parentRecord->workspace,
            'parent_record' => $childRecord->parentRecord,
            'child_record' => $childRecord,
        ]), [
            'minimum_value' => '10',
            'maximum_value' => null,
            'name' => 'Open Ended Leaf',
        ]);

        $response->assertRedirectBackWithErrors([
            'maximum_value' => 'Only one open-ended range is allowed.',
        ]);
    });
});
```

## Related References

- [`../request-and-range-validation.md`](../request-and-range-validation.md)
