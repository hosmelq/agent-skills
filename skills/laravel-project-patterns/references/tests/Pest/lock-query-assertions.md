# Lock Query Assertions

## When To Use

Use this leaf only when an existing justified database lock must be asserted.

## Pattern

### Lock Query Assertions

This helper documents an existing lock; it does not justify adding one. Use it
only for a single-row consume/recheck transition whose competing consumers all
use that path, or after the live action is confirmed to participate in a
complete shared-root protocol whose competing actions lock the same stable row
in the same order. Do not use it merely because an action has a transaction,
checks an active state, moves/orders rows, selects an initial/default row, or
resembles a catalog example.

Use `assertDatabaseLockedForUpdate($parentRecord)` before the code under test
when an action integration test must prove `lockForUpdate()` was applied to
specific persisted records. Pass one model instance or an iterable of model
instances, plus an optional connection name:

```php
/**
 * @param iterable<Model>|Model $models
 */
function assertDatabaseLockedForUpdate(
    iterable|Model $models,
    null|string $connection = null,
): void {
    // Register the query listener now and assert each model lock at teardown.
}
```

The helper derives each model's table, key name, key, and default connection,
then requires a matching `FOR UPDATE` query and binding. It intentionally does
not accept model classes or table-name strings because those cannot identify
the exact record whose lock is part of the contract.

## Related References

- [Parent router](../Pest.md)
