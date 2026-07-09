# Test Suite Routing

## When To Use

Use this leaf to choose the behavior-owning test suite.

## Pattern

### Suite Routing

- Before adding or deleting a test, map it to the behavior owner: route contract, request rule, policy branch, resource shape, persistence invariant, changed behavior, or regression risk. Do not add tests only to mirror a template or sibling count.
- Use `Unit` when the behavior can be proven without HTTP and usually without persisted relationship graphs: enum values, model casts, model defaults, traits present on a class, simple accessors, and pure model methods.
- Use `Integration` when the behavior depends on persistence, factories, observers, resources, relationships, media library events, support adapters, external collaborator fakes, or database-enforced business invariants.
- Use `Feature` when the behavior enters through routes, HTTP verbs, Inertia pages, API JSON, middleware, route model binding, console commands, auth/session behavior, or redirects.
- Use `Browser` only when a real browser is needed for browser-side behavior. There is no current `tests/Browser` tree, so do not create one casually.
- Use architecture tests for broad static rules that should hold across namespaces, not for feature behavior.
- For nested web controller work, treat the full route chain as behavior. Test every ancestor and leaf binding boundary that the route can reject, including soft-deleted ancestors/leaves when the models use `SoftDeletes`.
- Exact JSON resource contracts belong in `tests/Integration/Http/Resources`, even when controller tests assert selected Inertia paths that depend on the same resource output.
- Database check constraints and partial unique indexes that enforce system rules belong in `tests/Integration/Models` when they can be proven by direct persistence. Examples include coordinate range checks and active-name/default-row uniqueness per parent.
- Layered coverage is not duplication when each suite proves a different owner. Keep both an integration model test for direct database enforcement and a controller feature test for HTTP validation, redirects, and messages when the same invariant exists at both layers. Remove or avoid only tests that re-prove the same owner twice, such as a controller test that only asserts a database constraint without an HTTP contract or a model integration test that only mirrors request validation.

## Related References

- [Parent router](../README.md)
