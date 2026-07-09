# Range Guards: Scope And Recreation

## When To Use

Read this leaf when scope and recreation is in scope for Range Guards.

## Pattern

```php
it('creates the same range in a different child record', function (): void {
    $childRecord = ChildRecord::factory()->createOne();
    $otherChildRecord = ChildRecord::factory()
        ->for($childRecord->parentRecord)
        ->createOne();

    LeafRecord::factory()
        ->for($childRecord)
        ->forRange(0, 2)
        ->createOne();

    resolve(CreateLeafRecord::class)->handle(
        $otherChildRecord,
        CreateLeafRecordInput::from([
            'maximum_value' => '2',
            'minimum_value' => '0',
            'name' => 'Other Leaf',
            'amount' => '12.50',
        ]),
    );

    assertDatabaseHas(LeafRecord::class, [
        'child_record_id' => $otherChildRecord->id,
        'maximum_value' => '2.0000',
        'minimum_value' => '0.0000',
    ]);
});

it('recreates a range after soft delete', function (): void {
    $leafRecord = LeafRecord::factory()
        ->forRange(5, null)
        ->createOne();
    $childRecord = $leafRecord->childRecord;

    $leafRecord->delete();

    resolve(CreateLeafRecord::class)->handle(
        $childRecord,
        CreateLeafRecordInput::from([
            'maximum_value' => null,
            'minimum_value' => '5',
            'name' => 'Replacement Open Ended Leaf',
            'amount' => '12.50',
        ]),
    );

    assertDatabaseHas(LeafRecord::class, [
        'child_record_id' => $childRecord->id,
        'maximum_value' => null,
        'minimum_value' => '5.0000',
    ]);

    assertSoftDeleted($leafRecord);
});
```

## Related References

- [`../range-guards.md`](../range-guards.md)
