# Three-Resource Store Tests: Delegated Success

## When To Use

Read this leaf when delegated success for `Three-Resource Route Chain` is in scope.

## Pattern

```php
describe('store', function (): void {
    it('creates a child record through the delegated action', function (): void {
        $parentRecord = ParentRecord::factory()->createOne();
        $childRecord = ChildRecord::factory()
            ->for($parentRecord)
            ->createOne();

        login(workspace: $parentRecord->workspace);

        mock(CreateChildRecord::class)
            ->shouldReceive('handle')
            ->once()
            ->withArgs(fn (
                ParentRecord $parentRecordArgument,
                ExampleInput $input
            ): bool => $parentRecordArgument->is($parentRecord)
                && $input->name === 'Example Child')
            ->andReturn($childRecord);

        $response = post(route('workspaces.parent-records.children.store', [
            'workspace' => $parentRecord->workspace,
            'parent_record' => $parentRecord,
        ]), [
            'name' => 'Example Child',
        ]);

        $response->assertRedirectToRoute('workspaces.parent-records.children.show', [
            'workspace' => $parentRecord->workspace,
            'parent_record' => $parentRecord,
            'child_record' => $childRecord,
        ])
            ->assertToast('Child record created');
    });
});
```

## Related References

- [`../three-resource.md`](../three-resource.md)
