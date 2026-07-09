# Controller Feature Tests

## Purpose

This reference defines conventions for controller feature tests under `tests/Feature/Http/Controllers/**`.

## When To Use

Use this reference when the touched test is an HTTP controller feature test, including web/session controllers and JSON API controllers. Use it when the file contains controller action groups such as `describe('index')`, `describe('store')`, or `describe('update')`, route helpers, scoped route parameters, authorization assertions, Inertia assertions, JSON assertions, or request validation assertions.

Do not use this reference for model/unit tests, action integration tests, console tests, support tests, or non-HTTP behavior.

## Required Pattern

### Focused References

- [Controller Test Workflow And Order](workflow-and-order.md): Use this leaf to select controller-test evidence and place cases in canonical order.
- [Controller Route Shape Coverage](route-shape-coverage.md): Use this leaf to select the applicable nested route shape and coverage depth.
- [Controller Transport Baselines](transport-baselines.md): Use this leaf for web/session versus JSON baseline assertions.
- [Action Routers](actions/README.md): select the live action and route depth.
- [Route Patterns](route-patterns.md): select binding order and route-chain shape.
- [API Mode](modes/api-json.md): select public or protected JSON transport.
- [Pattern Catalog](pattern-catalog.md): select a cross-cutting HTTP scenario.
- [Validation Catalog](validation/dataset-catalog.md): select only request rules
  present in the live Form Request.

## Coverage Expectations

Every routed controller action should have a matching action block unless the route intentionally excludes it. For nested controllers, compare against an equivalent live sibling with the same route depth, binding ownership, middleware, and response mode before declaring coverage complete. A deeper but behaviorally different sibling is not authoritative. When a child stores redundant `Workspace` or ancestor ownership, member actions must assert same-parent mismatched ownership returns `404`, and index actions must assert those rows are excluded.

Scoped uniqueness belongs near the mutation action: same-scope duplicate fails, duplicate outside the scope succeeds when allowed, current value is allowed on update, soft-deleted reuse succeeds when the rule excludes trashed rows, and inactive-but-reserved rows remain blocked when the rule still counts them.

## Do Not

- Do not contradict the skill non-negotiables or project conventions.
- Do not keep or recreate per-module test lists in this skill.
- Do not remove controller entry-point tests because action integration tests cover internal behavior.
- Do not use real module, route, or entity names in examples.

## Related References

- [`actions/README.md`](actions/README.md)
- [`delegated-action-contracts.md`](delegated-action-contracts.md)
- [`entrypoint-contracts.md`](entrypoint-contracts.md)
- [`references/tests/Feature/Http/Controllers/pattern-catalog.md`](pattern-catalog.md)
- [`references/tests/Feature/Http/Controllers/route-patterns.md`](route-patterns.md)
- [`references/tests/Feature/Http/Controllers/actions/*.md`](actions/README.md)
- [`references/tests/Feature/Http/Controllers/modes/api-json.md`](modes/api-json.md)
- [`references/tests/Feature/Http/Controllers/validation/*.md`](validation/dataset-catalog.md)
