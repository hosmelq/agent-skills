# Range Update Guards: Nullable And Open-Ended Updates

## When To Use

Read this leaf when nullable and open-ended updates is in scope for Range Update Guards.

## Pattern

```php
it('clears nullable range fields', function (): void {
    $leafRecord = LeafRecord::factory()
        ->forRange(2, 10)
        ->createOne();

    resolve(UpdateLeafRecord::class)->handle(
        $leafRecord,
        UpdateLeafRecordInput::from(['maximum_value' => null]),
    );

    assertDatabaseHas(LeafRecord::class, [
        'id' => $leafRecord->id,
        'maximum_value' => null,
    ]);
});

it('allows minimum value updates when the stored maximum value is open ended', function (): void {
    $leafRecord = LeafRecord::factory()
        ->forRange(5, null)
        ->createOne();

    resolve(UpdateLeafRecord::class)->handle(
        $leafRecord,
        UpdateLeafRecordInput::from(['minimum_value' => '11']),
    );

    assertDatabaseHas(LeafRecord::class, [
        'id' => $leafRecord->id,
        'maximum_value' => null,
        'minimum_value' => '11.0000',
    ]);
});
```

## Related References

- [`../range-update-guards.md`](../range-update-guards.md)
