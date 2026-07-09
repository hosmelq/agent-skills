# Data Input Actions: Public ID Resolution

## When To Use

Read this leaf when public id resolution is the action-owned contract.

## Pattern

When a create action receives a public related-record ID, prove the action
resolves it inside the correct owner scope:

```php
it('creates a child record with a related record public id', function (): void {
    $parentRecord = ParentRecord::factory()->createOne();
    $relatedRecord = RelatedRecord::factory()
        ->for($parentRecord->workspace)
        ->createOne();

    $childRecord = resolve(CreateChildRecord::class)->handle(
        $parentRecord,
        CreateChildRecordInput::from([
            'related_record_id' => $relatedRecord->public_id,
        ]),
    );

    assertDatabaseHas(ChildRecord::class, [
        'id' => $childRecord->id,
        'parent_record_id' => $parentRecord->id,
        'related_record_id' => $relatedRecord->id,
    ]);
});
```

## Related References

- [`../data-input-actions.md`](../data-input-actions.md)
