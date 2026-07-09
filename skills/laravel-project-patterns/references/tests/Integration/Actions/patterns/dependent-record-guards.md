# Dependent-Record Guards

## When To Use

Read this focused reference when the task involves dependent-record guards.

## Pattern

### Dependent-Record Guards

Dependent-record guards should include the variants that the action contract distinguishes. If active dependents block the action but inactive or soft-deleted dependents are ignored, show that explicitly:

```php
it('rejects parent records with active child records', function (): void {
    $parentRecord = ParentRecord::factory()->createOne();

    ChildRecord::factory()
        ->for($parentRecord)
        ->createOne();

    expect(fn () => resolve(DeactivateParentRecord::class)->handle($parentRecord))
        ->toThrow(
            CannotDeactivateParentRecord::class,
            'Cannot deactivate a parent record with active child records.',
        );

    assertDatabaseHas(ParentRecord::class, [
        'id' => $parentRecord->id,
        'deactivated_at' => null,
    ]);
});

it('deactivates when related child records are inactive', function (): void {
    $parentRecord = ParentRecord::factory()->createOne();

    ChildRecord::factory()
        ->inactive()
        ->for($parentRecord)
        ->createOne();

    resolve(DeactivateParentRecord::class)->handle($parentRecord);

    assertDatabaseHas(ParentRecord::class, [
        'id' => $parentRecord->id,
        'deactivated_at' => now(),
    ]);
});

it('deactivates when related child records are soft deleted', function (): void {
    $parentRecord = ParentRecord::factory()->createOne();

    ChildRecord::factory()
        ->trashed()
        ->for($parentRecord)
        ->createOne();

    resolve(DeactivateParentRecord::class)->handle($parentRecord);

    assertDatabaseHas(ParentRecord::class, [
        'id' => $parentRecord->id,
        'deactivated_at' => now(),
    ]);
});
```

If the action intentionally checks dependents with `withTrashed()`, soft-deleted dependents should still block and the parent should remain unchanged:

```php
it('rejects parent records with soft deleted child records when deletion checks all dependents', function (): void {
    $childRecord = ChildRecord::factory()->trashed()->createOne();

    expect(fn () => resolve(DeleteParentRecord::class)->handle($childRecord->parentRecord))
        ->toThrow(
            CannotDeleteParentRecord::class,
            'Cannot delete a parent record with dependent child records.',
        );

    assertNotSoftDeleted($childRecord->parentRecord);
});
```

## Related References

- [`../README.md`](../README.md)
