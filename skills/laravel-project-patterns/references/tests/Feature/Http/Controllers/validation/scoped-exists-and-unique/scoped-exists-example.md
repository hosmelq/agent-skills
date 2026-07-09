# Scoped `exists` Example

## When To Use

Read this focused reference when the task involves scoped `exists` example.

## Pattern

### Scoped `exists` Example

```php
it('validates related record belongs to the route Workspace', function (): void {
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
```

For the malformed public-ID dataset and controller-resolution companion, use
the [prepare-for-validation router](../prepare-for-validation.md).

## Related References

- [`../scoped-exists-and-unique.md`](../scoped-exists-and-unique.md)
