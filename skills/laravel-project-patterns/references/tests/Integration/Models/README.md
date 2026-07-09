# tests/Integration/Models

## Purpose

This reference defines conventions for persisted model tests under `tests/Integration/Models`.

## When To Use

Use this reference when your task matches this path and you need to follow its local conventions.

## Required Pattern

Use `tests/Integration/Models/<Model>Test.php` for persisted model behavior, relationships, observers, factories, slugs, `Workspace` ownership coherence, and model methods that require saved records.

### System-Logic-Only Policy

tests/Integration/Models/\*\* is for persisted system behavior only: observers, model methods requiring saved records, slugs/route keys, state transitions, persisted side effects, and domain-scoped relationship logic. Do not test generic Laravel mechanics such as relationship loading, related-model type checks, FK/ID equality, or factory/count smoke checks.

### Focused References

- [Persisted System Logic And Database Invariants](patterns/system-logic-and-database.md): Use this leaf for persisted model system behavior and direct database invariants.
- [Observers And Side Effects](patterns/observers-and-side-effects.md): persisted observer-managed behavior.
- [Factory Graph Coherence](patterns/factory-graph-coherence.md): coherent ownership graphs in persisted tests.
- [Slugs And Route Keys](patterns/slugs-and-route-keys.md): Use this leaf for persisted slug and route-key behavior.
- [Current Workspace State](patterns/current-workspace-state.md): Use this leaf for persisted current-Workspace transitions.

## Coverage Expectations

Cover only persisted system behavior for models in this path.
Do not add coverage here as a proxy for controller binding, resource serialization, or factory relationship wiring. Put those tests in the path that owns the behavior.
When a model invariant has both direct database enforcement and HTTP validation, keep both tests: integration proves the database cannot be bypassed; controller feature tests prove HTTP validation and redirects.
Do not collapse those into one suite unless one layer no longer owns the behavior. Invalid duplication is re-testing the same owner twice, not proving the same invariant through its database and HTTP contracts.

## Do Not

- Do not contradict the skill non-negotiables or project conventions.
- Do not add generic Laravel relationship mechanic assertions (loading, related-model type checks, FK/ID equality, or factory/count smoke checks).

## Related References

- [`references/app/Models/README.md`](../../../app/Models/README.md)
- [`references/tests/Unit/Models/README.md`](../../Unit/Models/README.md)
