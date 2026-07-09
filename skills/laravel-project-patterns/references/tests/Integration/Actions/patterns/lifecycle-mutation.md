# Lifecycle Mutation

## When To Use

Read this focused reference when the task involves lifecycle mutation.

## Pattern

### Lifecycle Mutation

Lifecycle actions should prove their state transition directly:

```php
it('deactivates an active parent record', function (): void {
    $parentRecord = ParentRecord::factory()->createOne();

    resolve(DeactivateParentRecord::class)->handle($parentRecord);

    assertDatabaseHas(ParentRecord::class, [
        'id' => $parentRecord->id,
        'deactivated_at' => now(),
    ]);
});

it('reactivates a parent record', function (): void {
    $parentRecord = ParentRecord::factory()->inactive()->createOne();

    resolve(ReactivateParentRecord::class)->handle($parentRecord);

    assertDatabaseHas(ParentRecord::class, [
        'id' => $parentRecord->id,
        'deactivated_at' => null,
    ]);
});
```

## Related References

- [`../README.md`](../README.md)
