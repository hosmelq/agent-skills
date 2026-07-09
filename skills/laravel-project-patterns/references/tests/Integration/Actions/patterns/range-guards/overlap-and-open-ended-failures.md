# Range Guards: Overlap And Open-Ended Failures

## When To Use

Read this leaf when overlap and open-ended failures is in scope for Range Guards.

## Pattern

```php
it('rejects overlapping ranges', function (): void {
    $childRecord = ChildRecord::factory()->createOne();

    LeafRecord::factory()
        ->for($childRecord)
        ->forRange(0, 2)
        ->createOne();

    expect(fn () => resolve(CreateLeafRecord::class)->handle(
        $childRecord,
        CreateLeafRecordInput::from([
            'maximum_value' => '3',
            'minimum_value' => '1',
            'name' => 'Standard Leaf',
            'amount' => '12.50',
        ]),
    ))->toThrow(
        CannotCreateLeafRecord::class,
        'The range overlaps an existing record.',
    );

    assertDatabaseMissing(LeafRecord::class, [
        'child_record_id' => $childRecord->id,
        'name' => 'Standard Leaf',
    ]);
});

it('rejects a second open-ended range', function (): void {
    $childRecord = ChildRecord::factory()->createOne();

    LeafRecord::factory()
        ->for($childRecord)
        ->forRange(5, null)
        ->createOne();

    expect(fn () => resolve(CreateLeafRecord::class)->handle(
        $childRecord,
        CreateLeafRecordInput::from([
            'maximum_value' => null,
            'minimum_value' => '10',
            'name' => 'Open Ended Leaf',
            'amount' => '12.50',
        ]),
    ))->toThrow(
        CannotCreateLeafRecord::class,
        'Only one open-ended range is allowed per child record.',
    );
});
```

## Related References

- [`../range-guards.md`](../range-guards.md)
