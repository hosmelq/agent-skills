# Scoped Bulk Update

## When To Use

Read this focused reference when the task involves scoped bulk update.

## Pattern

### Scoped Bulk Update

For an action that updates siblings in the same scope, test the scope behavior and back uniqueness with a database constraint:

```php
it('only clears default child records for the same parent record', function (): void {
    $parentRecord = ParentRecord::factory()->createOne();
    $otherParentRecord = ParentRecord::factory()->createOne();

    $defaultChildRecord = ChildRecord::factory()->for($parentRecord)->default()->createOne();
    $otherDefaultChildRecord = ChildRecord::factory()->for($otherParentRecord)->default()->createOne();
    $newDefaultChildRecord = ChildRecord::factory()->for($parentRecord)->createOne(['is_default' => false]);

    resolve(SetDefaultChildRecord::class)->handle($newDefaultChildRecord);

    assertDatabaseHas(ChildRecord::class, [
        'id' => $defaultChildRecord->id,
        'is_default' => false,
    ]);
    assertDatabaseHas(ChildRecord::class, [
        'id' => $otherDefaultChildRecord->id,
        'is_default' => true,
    ]);
    assertDatabaseHas(ChildRecord::class, [
        'id' => $newDefaultChildRecord->id,
        'is_default' => true,
    ]);
});
```

## Related References

- [`../README.md`](../README.md)
