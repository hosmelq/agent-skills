# Range Update Guards: Self Exclusion

## When To Use

Read this leaf when self exclusion is in scope for Range Update Guards.

## Pattern

```php
it('excludes the updated leaf record from overlap validation', function (): void {
    $leafRecord = LeafRecord::factory()
        ->forRange(0, 2)
        ->createOne(['name' => 'Original Leaf']);

    resolve(UpdateLeafRecord::class)->handle(
        $leafRecord,
        UpdateLeafRecordInput::from(['name' => 'Updated without range conflict']),
    );

    assertDatabaseHas(LeafRecord::class, [
        'id' => $leafRecord->id,
        'maximum_value' => '2.0000',
        'minimum_value' => '0.0000',
        'name' => 'Updated without range conflict',
    ]);
});
```

## Related References

- [`../range-update-guards.md`](../range-update-guards.md)
