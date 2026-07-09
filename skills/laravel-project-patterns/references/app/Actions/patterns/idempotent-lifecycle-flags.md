# Idempotent Lifecycle Flags

## When To Use

Use when lifecycle transitions also preserve or reset a distinguished boolean
flag, rather than only setting or clearing a timestamp.

## Pattern

Reject deactivation or deletion only while the record is both active and
selected. Repeated deactivation preserves the first timestamp:

```php
if ($parentRecord->is_selected && $parentRecord->isActive()) {
    throw CannotDeactivateParentRecord::becauseItIsActiveSelection();
}

if ($parentRecord->deactivated_at === null) {
    $parentRecord->update(['deactivated_at' => now()]);
}
```

Reactivation clears the lifecycle timestamp but does not silently restore the
distinguished state:

```php
$parentRecord->update([
    'deactivated_at' => null,
    'is_selected' => false,
]);
```

Deletion uses the same active-selection guard when that is the domain
invariant. Selection, activation, deactivation, and deletion remain separate
actions with separate tests.

## Related References

- [`../README.md`](../README.md)
- [`ensure-exclusive-state.md`](ensure-exclusive-state.md)
- [`references/tests/Integration/Actions/scenario-catalog/state-and-order-actions.md`](../../../tests/Integration/Actions/scenario-catalog/state-and-order-actions.md)
