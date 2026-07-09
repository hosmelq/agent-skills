# Exact Resource Contract Shape

## When To Use

Use this leaf for full-array resource assertion setup and style.

## Pattern

### File Shape

- Create one persisted model with its factory; read ordinary expected values from the model.
- Convert through the model's resource path:

```php
$resource = json_decode($model->toResource()->toJson(), true);
```

- Assert the full value contract with `toEqual([...])`.
- Keep key order in the expected array aligned with the resource output for readability. If key order itself is the contract, assert `array_keys($resource)` with `toBe([...])`; `toEqual([...])` alone does not fail on reordered associative keys.


### Required Assertion Style

Resource tests should assert the full serialized array value contract. Do not use partial match assertions for primary contract coverage.

Expected pattern:

```php
expect($resource)->toEqual([
    'created_at' => $model->created_at->toJSON(),
    'id' => $model->public_id,
    'name' => $model->name,
    'updated_at' => $model->updated_at->toJSON(),
]);
```

Derived deterministic field pattern:

```php
$actor = Actor::factory()->createOne([
    'contact_email' => ' actor@example.test ',
]);

$resource = json_decode($actor->toResource()->toJson(), true);

expect($resource)->toEqual([
    'avatar_url' => sprintf(
        'https://avatar.example/%s',
        '13f1cbf5226d40a9edc5bfcd7977fdfaa543f5cd85bb171d778eaf23977ce2fb'
    ),
    'contact_email' => $actor->contact_email,
    'id' => $actor->public_id,
]);
```

## Related References

- [Parent router](../README.md)
