# Range Update Guards: Overlap And Open-Ended Failures

## When To Use

Read this leaf when overlap and open-ended failures is in scope for Range Update Guards.

## Pattern

```php
it('rejects overlapping ranges when updating a leaf record', function (): void {
    $leafRecord = LeafRecord::factory()
        ->forRange(3, 5)
        ->createOne(['name' => 'Original Leaf']);

    LeafRecord::factory()
        ->recycle($leafRecord->childRecord)
        ->forRange(0, 2)
        ->createOne();

    expect(fn () => resolve(UpdateLeafRecord::class)->handle(
        $leafRecord,
        UpdateLeafRecordInput::from([
            'maximum_value' => '4',
            'minimum_value' => '1',
        ]),
    ))->toThrow(
        CannotUpdateLeafRecord::class,
        'The range overlaps an existing record.',
    );

    assertDatabaseHas(LeafRecord::class, [
        'id' => $leafRecord->id,
        'maximum_value' => '5.0000',
        'minimum_value' => '3.0000',
        'name' => 'Original Leaf',
    ]);
});

it('rejects updating a leaf record to a second open-ended range', function (): void {
    $leafRecord = LeafRecord::factory()
        ->forRange(0, 5)
        ->createOne();

    LeafRecord::factory()
        ->recycle($leafRecord->childRecord)
        ->forRange(10, null)
        ->createOne();

    expect(fn () => resolve(UpdateLeafRecord::class)->handle(
        $leafRecord,
        UpdateLeafRecordInput::from(['maximum_value' => null]),
    ))->toThrow(
        CannotUpdateLeafRecord::class,
        'Only one open-ended range is allowed per child record.',
    );

    assertDatabaseHas(LeafRecord::class, [
        'id' => $leafRecord->id,
        'maximum_value' => '5.0000',
    ]);
});
```

## Related References

- [`../range-update-guards.md`](../range-update-guards.md)
