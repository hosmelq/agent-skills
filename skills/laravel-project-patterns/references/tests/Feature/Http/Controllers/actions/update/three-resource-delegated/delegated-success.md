# Three-Resource Delegated Update Tests: Delegated Success

## When To Use

Read this leaf when delegated success for `Three-Resource Route Chain` is in scope.

## Pattern

```php
describe('update', function (): void {
    it('passes partial input to the delegated action', function (): void {
        $childRecord = ChildRecord::factory()->createOne();

        login(workspace: $childRecord->parentRecord->workspace);

        mock(UpdateChildRecord::class)
            ->shouldReceive('handle')
            ->once()
            ->withArgs(fn (
                ChildRecord $childRecordArgument,
                ExampleInput $input
            ): bool => $childRecordArgument->is($childRecord)
                && $input->name === 'Updated Child');

        $response = patch(route('workspaces.parent-records.children.update', [
            'workspace' => $childRecord->parentRecord->workspace,
            'parent_record' => $childRecord->parentRecord,
            'child_record' => $childRecord,
        ]), [
            'name' => 'Updated Child',
        ]);

        $response->assertRedirectToRoute('workspaces.parent-records.children.show', [
            'workspace' => $childRecord->parentRecord->workspace,
            'parent_record' => $childRecord->parentRecord,
            'child_record' => $childRecord,
        ])
            ->assertToast('Child record updated');
    });
});
```

## Related References

- [`../three-resource-delegated.md`](../three-resource-delegated.md)
