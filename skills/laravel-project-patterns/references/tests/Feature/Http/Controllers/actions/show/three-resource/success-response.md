# Three-Resource Show Tests: Success Response

## When To Use

Read this leaf when success response for `Three-Resource Route Chain` is in scope.

## Pattern

```php
describe('show', function (): void {
    it('shows a child record', function (): void {
        $childRecord = ChildRecord::factory()->createOne();

        login(workspace: $childRecord->parentRecord->workspace);

        $response = get(route('workspaces.parent-records.children.show', [
            'workspace' => $childRecord->parentRecord->workspace,
            'parent_record' => $childRecord->parentRecord,
            'child_record' => $childRecord,
        ]));

        $response->assertOk()
            ->assertInertia(function (AssertableInertia $page) use ($childRecord): void {
                $page->component('children/Show')
                    ->where('workspace.id', $childRecord->parentRecord->workspace->public_id)
                    ->where('parentRecord.id', $childRecord->parentRecord->public_id)
                    ->where('childRecord.id', $childRecord->public_id);
            });
    });
});
```

## Related References

- [`../three-resource.md`](../three-resource.md)
