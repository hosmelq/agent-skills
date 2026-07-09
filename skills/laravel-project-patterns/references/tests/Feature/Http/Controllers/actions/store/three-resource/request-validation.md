# Three-Resource Store Tests: Request Validation

## When To Use

Read this leaf when request validation for `Three-Resource Route Chain` is in scope.

## Pattern

```php
describe('store', function (): void {
    it('validates related record belongs to the parent record scope', function (): void {
        $parentRecord = ParentRecord::factory()->createOne();
        $relatedRecord = RelatedRecord::factory()->createOne();

        login(workspace: $parentRecord->workspace);

        $response = post(route('workspaces.parent-records.children.store', [
            'workspace' => $parentRecord->workspace,
            'parent_record' => $parentRecord,
        ]), [
            'related_record_id' => $relatedRecord->public_id,
        ]);

        $response->assertRedirectBackWithErrors([
            'related_record_id' => 'The selected related record id is invalid.',
        ]);
    });
});
```

## Related References

- [`../three-resource.md`](../three-resource.md)
