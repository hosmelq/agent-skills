# Range Guards: Adjacency And Soft Delete

## When To Use

Read this leaf when adjacency and soft delete is in scope for Range Guards.

## Pattern

```php
it('creates adjacent ranges within the same child record', function (): void {
    $childRecord = ChildRecord::factory()->createOne();

    LeafRecord::factory()
        ->for($childRecord)
        ->forRange(0, 1)
        ->createOne();

    resolve(CreateLeafRecord::class)->handle(
        $childRecord,
        CreateLeafRecordInput::from([
            'maximum_value' => '5',
            'minimum_value' => '1',
            'name' => 'Adjacent Leaf',
            'amount' => '12.50',
        ]),
    );

    resolve(CreateLeafRecord::class)->handle(
        $childRecord,
        CreateLeafRecordInput::from([
            'maximum_value' => null,
            'minimum_value' => '5',
            'name' => 'Open Ended Leaf',
            'amount' => '12.50',
        ]),
    );

    $leafRecordCount = $childRecord->leafRecords()->count();

    expect($leafRecordCount)->toBe(3);
});

it('ignores soft deleted records when creating ranges', function (): void {
    $childRecord = ChildRecord::factory()->createOne();
    $leafRecord = LeafRecord::factory()
        ->for($childRecord)
        ->forRange(0, 2)
        ->createOne();

    $leafRecord->delete();

    resolve(CreateLeafRecord::class)->handle(
        $childRecord,
        CreateLeafRecordInput::from([
            'maximum_value' => '3',
            'minimum_value' => '1',
            'name' => 'Replacement Leaf',
            'amount' => '12.50',
        ]),
    );

    assertDatabaseHas(LeafRecord::class, [
        'child_record_id' => $childRecord->id,
        'maximum_value' => '3.0000',
        'minimum_value' => '1.0000',
    ]);

    assertSoftDeleted($leafRecord);
});
```

## Related References

- [`../range-guards.md`](../range-guards.md)
