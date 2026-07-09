# Existing Shared-Root Lock Protocols Only

## When To Use

Read this focused reference when the task involves existing shared-root lock protocols only.

## Pattern

### Existing Shared-Root Lock Protocols Only

Do not use this section to design a new lock protocol from an action category or
example. Start with direct mutations, transactions for atomic writes, and
database constraints. Use a shared-root lock only when the live domain already
establishes all of these facts:

1. the invariant spans rows and cannot reasonably be enforced by PostgreSQL;
2. a race exists between explicitly named competing actions;
3. every competing action locks the same stable root before reading or writing
   dependent state and acquires multiple locks in the same order;
4. the lock and mutation run in one transaction, with integration coverage for
   the business guard that requires serialization.

An active-state read, move/order operation, initial/default selection,
transaction, or multi-row write does not satisfy this gate by itself. A lock in
only some competing operations is an incomplete protocol, not a reason to add
locks mechanically to the rest.

Rejecting that incomplete protocol is a stop condition, not an instruction to
simulate the lock with more queries. Do not introduce a conditional
`UPDATE ... WHERE`, branch on affected rows, re-query with `exists()` or a
fresh model, retry, or choose another serialization mechanism solely because a
review comment identified a possible race. Those mechanisms are separate
concurrency designs and require independent live evidence: the invariant,
named competing operations, atomic predicate, conflict and idempotency
semantics, and focused tests. A follow-up query after a failed conditional
write does not itself serialize competitors or prove why the write failed.

Within an existing protocol, one guard action may lock and re-read the shared
root and return the locked model:

```php
public function handle(ParentRecord $parentRecord): ParentRecord
{
    $lockedParentRecord = ParentRecord::query()
        ->whereKey($parentRecord)
        ->lockForUpdate()
        ->firstOrFail();

    if ($lockedParentRecord->deactivated_at !== null) {
        throw ParentRecordIsInactive::make();
    }

    return $lockedParentRecord;
}
```

Call it inside the caller's transaction only for the named participants in
that protocol. Competing actions lock the same root in the same order. This is
not the default shape for create, update, delete, move/order, or lifecycle
actions. Within a verified protocol, guarded variants remain distinct:

- lock the target, then reject deactivation while active dependents exist;
- include `withTrashed()` when even soft-deleted dependents block permanent
  deletion;
- reject changing only the protected field after related leaves exist while
  allowing unrelated partial updates;
- keep direct idempotent lifecycle transitions direct when no cross-row guard
  exists.

## Related References

- [`../README.md`](../README.md)
