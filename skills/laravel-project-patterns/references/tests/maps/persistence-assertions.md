# Persistence Assertions

## When To Use

Use this leaf to choose database assertions or reloaded Eloquent expectations.

## Pattern

### Persistence Assertions

Neither `factory()->createOne()`, a factory state, nor an `afterCreating()`
hook requires `refresh()` or a database assertion by itself. Assert the
behavior named by the test; do not assert fixture setup.

Use database assertions when the contract is durable storage:

- `assertDatabaseHas()` for created or updated rows and normalized/resolved values.
- `assertDatabaseMissing()` for rows that must not exist.
- `assertSoftDeleted()` for soft-deleting models.
- `assertModelMissing()` for hard deletes.

Call `$model->refresh();` only when a database change made outside the already
loaded instance must be observed through that same Eloquent object. Factory
creation, casts, accessors, a first relationship access, timestamps, and
observer existence do not require refresh by themselves. Do not embed
`refresh()` or `fresh()` inside `expect(...)`. Do not assert the same ordinary
persisted field both ways unless the raw database value and the reloaded
Eloquent value are separate named contracts.

### Factory Decision Examples

### Factory Used Only As A Fixture

The factories establish the guard precondition. Assert the action behavior,
not that Laravel created the fixtures:

```php
it('rejects deleting a parent record with children', function (): void {
    $parentRecord = ParentRecord::factory()->createOne();

    ChildRecord::factory()->for($parentRecord)->createOne();

    expect(fn () => resolve(DeleteParentRecord::class)->handle($parentRecord))
        ->toThrow(CannotDeleteParentRecord::class);
});
```

### Factory State Used As A Precondition

Do not reload or separately assert a state when the observable result already
proves the scenario:

```php
it('rejects deleting a deactivated parent record', function (): void {
    $parentRecord = ParentRecord::factory()->deactivated()->createOne();

    expect(fn () => resolve(DeleteParentRecord::class)->handle($parentRecord))
        ->toThrow(CannotDeleteParentRecord::class);
});
```

### Persisted Factory State Is The Contract

When the test specifically owns an `afterCreating()` association or another
persisted state value, prove the stored value directly without refreshing the
model:

```php
it('persists the explicit related record state', function (): void {
    $childRecord = ChildRecord::factory()->withRelatedRecord()->createOne();

    assertDatabaseHas(ChildRecord::class, [
        'id' => $childRecord->id,
        'related_record_id' => $childRecord->related_record_id,
    ]);
});
```

### Reloaded Eloquent Behavior Is The Contract

Refresh only when a database change must be observed through the same Eloquent
instance:

```php
it('reloads a relationship changed outside the model instance', function (): void {
    $childRecord = ChildRecord::factory()->createOne([
        'related_record_id' => null,
    ]);

    $relatedRecord = RelatedRecord::factory()->createOne();

    ChildRecord::query()
        ->whereKey($childRecord->id)
        ->update(['related_record_id' => $relatedRecord->id]);

    $childRecord->refresh();

    expect($childRecord->relatedRecord->is($relatedRecord))->toBeTrue();
});
```

## Related References

- [Parent router](../README.md)
