# Test Design And Style Contracts

## When To Use

Use this leaf before adding, deleting, renaming, or changing Pest tests and
their fixtures or assertions.

## Pattern

- Every behavioral change needs a focused programmatic test.
- Every new test must prove changed behavior, an interface contract, a regression risk, or a changed owner surface. Do not add tests only because an action template contains a similar example.
- Name action integration tests after the observable behavior. For the primary create success case, use a direct name such as `creates a child record`; do not append the parent or owner merely because the persisted row includes its ID. Add a scope qualifier only when that scope is the behavior under test, such as cross-parent isolation or an active-parent guard.
- Before naming or structuring a test, search for an equivalent live sibling with the same precondition, operation, ownership boundary, and outcome; directory proximity alone is irrelevant. Equivalent scenarios must keep that sibling's grammatical template, complete domain nouns, fixture/action/assertion order, and assertion style; change only the entity names and domain-required qualifiers. Do not introduce shorthand, synonyms, avoidable relation preloading, or an avoidable `refresh()`. This aligns existing equivalent coverage without inventing new tests merely for symmetry. Read the [parallel test examples](../tests/maps/parallel-test-structure.md).
- Assert durable database effects with `assertDatabaseHas()`, `assertDatabaseMissing()`, `assertSoftDeleted()`, or `assertModelMissing()` according to the persistence contract. Neither creating a factory, applying a factory state, nor using `afterCreating()` requires `refresh()` or a database assertion by itself; do not assert fixture setup or prove the same ordinary persisted field again with `expect()`. Returned identity, collection counts, and relationship behavior are separate contracts and may use `expect()`. Read the four distinct [factory and persistence examples](../tests/maps/persistence-assertions.md) before choosing an assertion. If a test needs a soft-deleted fixture, create it with the factory `trashed()` state instead of creating an active model and calling `delete()`. Keep the soft-deleted parent in a local variable for route/login arguments because normal `belongsTo` queries may filter it out. If a child factory derives ownership through a normal parent query that cannot see trashed rows, pass only the minimum FK/owner IDs needed to create the child under that trashed parent. Use `$model->refresh();` only when a database change must be observed through that same already-loaded Eloquent instance. A relationship first accessed after the action already queries current state and needs no refresh; arrange fixtures to avoid preloading it when that preserves the equivalent sibling structure. When refresh is required, call it before opening `expect()` and never embed `refresh()` or `fresh()` inside an expectation.
- Keep related Pest assertions in one expectation chain. Instantiate every model or object before opening the chain; do not put `new ...` inside `expect()` or `and()`. When the first object has multiple checks, use higher-order expectations from that object; when it has only one check, pass the expression directly to `expect()`. Use `and()` when the chain changes to another subject, but do not reintroduce the same root object with `and()` merely to continue checking it. See `references/tests/Pest.md`.
- Keep `tests/Integration/Models/**` limited to project/system behavior. Use `references/tests/Integration/Models/README.md` for the canonical boundary and avoid generic Laravel relationship, FK/ID equality, related-model type, or factory/count smoke tests.
- Do not create only the most obvious test file. Update related tests when a change alters a related model, resource, controller, action, middleware, console command, or support surface.

## Related References

- [`references/tests/README.md`](../tests/README.md)
- [`references/tests/Pest.md`](../tests/Pest.md)
- [`references/tests/maps/parallel-test-structure.md`](../tests/maps/parallel-test-structure.md)
- [`references/tests/maps/persistence-assertions.md`](../tests/maps/persistence-assertions.md)
- [`references/tests/Integration/Actions/README.md`](../tests/Integration/Actions/README.md)
- [`references/tests/Integration/Models/README.md`](../tests/Integration/Models/README.md)
