# Data Input Actions: Update Persistence And Identity

## When To Use

Read this leaf when update persistence and identity is the action-owned contract.

## Pattern

Use this pattern when an action takes a Spatie Laravel Data input instead of a raw array:

```php
$updatedParentRecord = resolve(UpdateParentRecord::class)->handle(
    parentRecord: $parentRecord,
    input: UpdateParentRecordInput::from([
        'name' => 'Updated Parent',
        'enabled' => false,
    ]),
);

expect($updatedParentRecord->is($parentRecord))->toBeTrue();

assertDatabaseHas(ParentRecord::class, [
    'id' => $parentRecord->id,
    'name' => 'Updated Parent',
    'enabled' => false,
]);
```

## Related References

- [`../data-input-actions.md`](../data-input-actions.md)
