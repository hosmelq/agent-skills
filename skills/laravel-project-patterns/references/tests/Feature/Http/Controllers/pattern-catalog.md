# Controller Test Pattern Catalog

## Purpose

This catalog lists reusable controller-test patterns for the system. Use it to decide which test families apply to a new or changed controller. Do not copy it as a per-controller checklist.

## When To Use

Use this reference when a controller test needs a cross-cutting catalog of route shapes, ordering layers, binding boundaries, validation families, transport modes, and side-effect ownership.

## Required Pattern

Workflow:

1. Choose the route shape from `route-patterns.md`.
2. Choose the action baseline from `actions/*.md`.
3. Choose the transport guide from `modes/api-json.md` for JSON endpoints.
4. Merge only the validation references that match the real Form Request from `validation/*.md`.
5. Apply catalog rows only when the controller, route binding, policy, request, resource, or side effect makes the row reachable.

### Reference Map

- [`pattern-catalog/route-shape-action-matrix.md`](pattern-catalog/route-shape-action-matrix.md): Route Shape / Action Matrix.
- [`pattern-catalog/complete-per-action-order-matrix.md`](pattern-catalog/complete-per-action-order-matrix.md): Complete Per-Action Order Matrix.
- [`pattern-catalog/page-response-inertia-backend-contract-patterns.md`](pattern-catalog/page-response-inertia-backend-contract-patterns.md): Page Response / Inertia Backend Contract Patterns.
- [`pattern-catalog/access-order-patterns.md`](pattern-catalog/access-order-patterns.md): Access / Order Patterns.
- [`pattern-catalog/nested-binding-patterns.md`](pattern-catalog/nested-binding-patterns.md): Nested Binding Patterns.
- [`pattern-catalog/index-list-patterns.md`](pattern-catalog/index-list-patterns.md): Index / List Patterns.
- [`pattern-catalog/store-update-validation-patterns.md`](pattern-catalog/store-update-validation-patterns.md): Store / Update Validation Patterns.
- [`pattern-catalog/success-side-effect-patterns.md`](pattern-catalog/success-side-effect-patterns.md): Success / Side-Effect Patterns.
- [`pattern-catalog/focused-variant-examples.md`](pattern-catalog/focused-variant-examples.md): Focused Variant Examples.
- [`pattern-catalog/conditional-input-normalization.md`](pattern-catalog/conditional-input-normalization.md): Dependent values cleared or preserved from stored state during partial update mapping.
- [`pattern-catalog/conditional-store-input-normalization.md`](pattern-catalog/conditional-store-input-normalization.md): Store-only clearing of a submitted conditional value with no stored fallback.
- [`pattern-catalog/ordered-state-update.md`](pattern-catalog/ordered-state-update.md): Controller-owned category changes that normalize source and destination order.
- [`pattern-catalog/invokable-exclusive-selection.md`](pattern-catalog/invokable-exclusive-selection.md): Eligibility failures and HTTP success for an exclusive-selection endpoint.
- [`pattern-catalog/move.md`](pattern-catalog/move.md): Route-bound targets, move-after public-ID validation, group scoping, and conditional inactive-row movement.
- [`pattern-catalog/transport-api-patterns.md`](pattern-catalog/transport-api-patterns.md): Transport / API Patterns.
- [`pattern-catalog/applicability-rule.md`](pattern-catalog/applicability-rule.md): Applicability Rule.

## Coverage Expectations

Use this catalog only after loading the route shape, action template, transport mode, and validation references that match the touched controller. Coverage claims must map to live route, controller, request, policy, action, resource, or test evidence.

## Do Not

- Do not keep per-module test lists in this skill.
- Do not copy one module's domain example as a universal requirement.
- Do not create fake application PHP tests from this catalog.
- Do not drop controller entry-point coverage because action integration tests cover internal state.

## Related References

- [`references/tests/Feature/Http/Controllers/README.md`](README.md)
- [`references/tests/Feature/Http/Controllers/route-patterns.md`](route-patterns.md)
- [`references/tests/Feature/Http/Controllers/actions/*.md`](actions/README.md)
- [`references/tests/Feature/Http/Controllers/modes/api-json.md`](modes/api-json.md)
- [`references/tests/Feature/Http/Controllers/validation/*.md`](validation/dataset-catalog.md)
