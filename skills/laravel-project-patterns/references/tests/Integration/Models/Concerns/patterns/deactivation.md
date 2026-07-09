# Persisted Deactivation Concern

## When To Use

Use this leaf for the reusable persisted deactivation API.

## Pattern

### Deactivation Concern Pattern

For deactivation concerns, cover the reusable persisted API once through the generic test support model:

- active and deactivated state helpers;
- `active()` and `deactivated()` local scopes;
- `deactivate()` setting `deactivated_at` without overwriting an existing timestamp;
- `reactivate()` clearing `deactivated_at` while remaining idempotent for active records.

Use compact tests that group one concern behavior owner per test. It is also acceptable to split transition and idempotence branches into separate focused tests when that is clearer in the live sibling file. Do not create separate tests for every branch when the split does not clarify the reusable concern contract.

```php
it('detects active and deactivated models', function (): void {
    $activeModel = ExampleModel::query()->create();
    $deactivatedModel = ExampleModel::query()->create(['deactivated_at' => now()]);

    expect($activeModel)
        ->isActive()->toBeTrue()
        ->isDeactivated()->toBeFalse()
        ->and($deactivatedModel)
        ->isActive()->toBeFalse()
        ->isDeactivated()->toBeTrue();
});

it('scopes active and deactivated models', function (): void {
    $activeModel = ExampleModel::query()->create();
    $deactivatedModel = ExampleModel::query()->create(['deactivated_at' => now()]);

    $activeModelIds = ExampleModel::query()->active()->pluck('id')->all();
    $deactivatedModelIds = ExampleModel::query()->deactivated()->pluck('id')->all();

    expect($activeModelIds)
        ->toBe([$activeModel->id])
        ->and($deactivatedModelIds)
        ->toBe([$deactivatedModel->id]);
});

it('deactivates a model without replacing an existing timestamp', function (): void {
    $activeModel = ExampleModel::query()->create();
    $alreadyDeactivatedModel = ExampleModel::query()->create([
        'deactivated_at' => CarbonImmutable::today()->subDay(),
    ]);

    $activeModel->deactivate();
    $alreadyDeactivatedModel->deactivate();

    assertDatabaseHas(ExampleModel::class, [
        'id' => $activeModel->id,
        'deactivated_at' => now(),
    ]);
    assertDatabaseHas(ExampleModel::class, [
        'id' => $alreadyDeactivatedModel->id,
        'deactivated_at' => CarbonImmutable::today()->subDay(),
    ]);
});

it('reactivates a model and keeps active models unchanged', function (): void {
    $deactivatedModel = ExampleModel::query()->create(['deactivated_at' => now()]);
    $activeModel = ExampleModel::query()->create();

    $deactivatedModel->reactivate();
    $activeModel->reactivate();

    assertDatabaseHas(ExampleModel::class, [
        'id' => $deactivatedModel->id,
        'deactivated_at' => null,
    ]);
    assertDatabaseHas(ExampleModel::class, [
        'id' => $activeModel->id,
        'deactivated_at' => null,
    ]);
});
```

## Related References

- [Parent router](../README.md)
