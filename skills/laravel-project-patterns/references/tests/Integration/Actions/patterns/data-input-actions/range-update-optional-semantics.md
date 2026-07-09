# Data Input Actions: Range Update Optional Semantics

## When To Use

Read this leaf when range update optional semantics is the action-owned contract.

## Pattern

For half-open range updates, keep omission, explicit `null`, and an already
open stored maximum as separate persistence contracts:

```php
it('updates only provided range fields', function (): void {
    $leafRecord = LeafRecord::factory()
        ->forRange(1, 10)
        ->createOne(['name' => 'Original']);

    resolve(UpdateLeafRecord::class)->handle(
        $leafRecord,
        UpdateLeafRecordInput::from([
            'name' => 'Updated without range conflict',
        ]),
    );

    assertDatabaseHas(LeafRecord::class, [
        'id' => $leafRecord->id,
        'maximum_value' => '10.0000',
        'minimum_value' => '1.0000',
        'name' => 'Updated without range conflict',
    ]);
});

it('clears the nullable maximum value', function (): void {
    $leafRecord = LeafRecord::factory()
        ->forRange(2, 10)
        ->createOne();

    resolve(UpdateLeafRecord::class)->handle(
        $leafRecord,
        UpdateLeafRecordInput::from([
            'maximum_value' => null,
        ]),
    );

    assertDatabaseHas(LeafRecord::class, [
        'id' => $leafRecord->id,
        'maximum_value' => null,
    ]);
});

it('updates the minimum value when the stored maximum is open ended', function (): void {
    $leafRecord = LeafRecord::factory()
        ->forRange(5, null)
        ->createOne();

    resolve(UpdateLeafRecord::class)->handle(
        $leafRecord,
        UpdateLeafRecordInput::from([
            'minimum_value' => '11',
        ]),
    );

    assertDatabaseHas(LeafRecord::class, [
        'id' => $leafRecord->id,
        'maximum_value' => null,
        'minimum_value' => '11.0000',
    ]);
});
```

## Related References

- [`../data-input-actions.md`](../data-input-actions.md)
