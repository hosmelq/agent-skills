# Two-Resource Delegated Update Tests: Delegated Success

## When To Use

Read this leaf when delegated success for `Two-Resource Route Chain` is in scope.

## Pattern

```php
describe('update', function (): void {
    it('updates a parent record', function (): void {
        $parentRecord = ParentRecord::factory()->createOne();

        login(workspace: $parentRecord->workspace);

        mock(UpdateParentRecord::class)
            ->shouldReceive('handle')
            ->once()
            ->withArgs(fn (
                ParentRecord $parentRecordArgument,
                UpdateParentRecordInput $input
            ): bool => $parentRecordArgument->is($parentRecord)
                && $input->exampleMode === ExampleMode::Secondary
                && $input->name === 'Updated Parent');

        $response = patch(route('workspaces.parent-records.update', [
            'workspace' => $parentRecord->workspace,
            'parent_record' => $parentRecord,
        ]), [
            'example_mode' => ExampleMode::Secondary->value,
            'name' => 'Updated Parent',
        ]);

        $response->assertRedirectToRoute('workspaces.parent-records.show', [
            'workspace' => $parentRecord->workspace,
            'parent_record' => $parentRecord,
        ])
            ->assertToast('Parent record updated');
    });
});
```

## Related References

- [`../two-resource-delegated.md`](../two-resource-delegated.md)
