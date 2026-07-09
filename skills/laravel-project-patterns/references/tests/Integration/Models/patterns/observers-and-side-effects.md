# Observers And Side Effects

## When To Use

Use for persisted observer-managed state and side effects.

## Pattern

Cover behavior such as:

- only one default child per parent;
- preserving existing data when create or update fails;
- soft-deleted record behavior;
- cross-parent isolation;
- updates that must not trigger observer logic.

Use `assertDatabaseHas()` for ordinary persisted side effects. Refresh only
when the named contract requires an already-loaded Eloquent instance to observe
a database change made outside that instance; do not refresh after factory
creation or merely because an observer ran.

## Related References

- [Parent router](../README.md)
