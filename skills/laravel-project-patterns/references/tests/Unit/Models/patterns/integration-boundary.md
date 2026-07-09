# Unit To Integration Boundary

## When To Use

Use this leaf to decide when model behavior requires the integration suite.

## Pattern

### Relationship Split

Do not test loaded relationship graphs here. Use `tests/Integration/Models` for persisted domain behavior such as observer side effects, factory coherence that matters to business behavior, route key persistence, and cross-model behavior.

Do not test database constraints here. For example, coordinate range checks and active-name uniqueness are integration model tests because they require direct persistence against the database.


### Integration Boundary

For persisted model behavior, load `references/tests/Integration/Models/README.md`; do not repeat integration policy in unit-model tests.

## Related References

- [Parent router](../README.md)
