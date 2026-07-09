# Top-Level Update

## When To Use

Read this focused reference when the task involves top-level update.

## Pattern

### Top-Level Update

Test the persisted behavior owned by the action:

```php
it('updates only provided parent record fields', function (): void {
    $parentRecord = ParentRecord::factory()->createOne([
        'description' => 'Original description',
        'name' => 'Original Parent',
    ]);

    resolve(UpdateParentRecord::class)->handle(
        $parentRecord,
        UpdateParentRecordInput::from(['name' => 'Updated Parent']),
    );

    assertDatabaseHas(ParentRecord::class, [
        'id' => $parentRecord->id,
        'description' => 'Original description',
        'name' => 'Updated Parent',
    ]);
});

it('clears nullable parent record fields', function (): void {
    $parentRecord = ParentRecord::factory()->createOne([
        'description' => 'Original description',
    ]);

    resolve(UpdateParentRecord::class)->handle(
        $parentRecord,
        UpdateParentRecordInput::from(['description' => null]),
    );

    assertDatabaseHas(ParentRecord::class, [
        'id' => $parentRecord->id,
        'description' => null,
    ]);
});
```

## Related References

- [`../README.md`](../README.md)
