# Store Action Templates

## Purpose

This reference defines `describe('store')` patterns for controller feature tests.

## When To Use

Use this reference when a web/session controller exposes a `store` action. For JSON endpoints, keep the same validation and side-effect discipline and adapt assertions with `../modes/api-json.md`.

## Required Pattern

The store controller test always proves the HTTP entry point. Mock the delegated Data input-backed action and assert request-to-input mapping, route-bound parent identity, redirect/toast, and exception-to-validation mapping. Persistence, defaults, generated values, transactions, locks, and domain guards belong in `tests/Integration/Actions`.

Apply the [shared actor context](README.md#shared-actor-context).

For three- and four-resource chains, prepend the full collection binding order from `../route-patterns.md`: authentication, unrelated Workspace authorization, outer parent wrong Workspace, outer parent soft-deleted, child wrong parent in the same Workspace, child wrong Workspace, child soft-deleted, then lifecycle, validation, mapped action exceptions, and success.

After the base validation dataset, add named validation tests for scoped uniqueness, parent-dependent `exists`, stored public-ID resolution, and request-owned cross-field rules. Put action-owned domain guards in the action suite and keep the controller test for mocked exception mapping.

### Reference Map

- [`store/two-resource.md`](store/two-resource.md): Two-Resource Route Chain (`workspaces.parent-records.store`).
- [`store/three-resource.md`](store/three-resource.md): Three-Resource Route Chain (`workspaces.parent-records.children.store`).
- [`store/four-resource.md`](store/four-resource.md): Four-Resource Route Chain (`workspaces.parent-records.children.leaves.store`).

### Additional Validation References

Load focused validation files before the broader catalog:

- [`references/tests/Feature/Http/Controllers/validation/required-with-and-array.md`](../validation/required-with-and-array.md)
- [`references/tests/Feature/Http/Controllers/validation/scoped-exists-and-unique.md`](../validation/scoped-exists-and-unique.md)
- [`references/tests/Feature/Http/Controllers/validation/prepare-for-validation.md`](../validation/prepare-for-validation.md)
- [`references/tests/Feature/Http/Controllers/validation/store-validates-fields.md`](../validation/store-validates-fields.md)

### Focused References

- [System Store Patterns](store/system-patterns.md): Use this leaf to select distinct delegated store and validation scenarios.

## Coverage Expectations

Use the live controller, routes, form requests, policies, resources, actions,
and equivalent live siblings with the same route shape, request rules, and
mutation ownership to decide the complete store matrix. Named tests are
preferred for validation that needs persisted rows.

## Do Not

- Do not contradict the skill non-negotiables or project conventions.
- Do not drop applicable controller boundary coverage when adapting examples.
- Do not use real module, route, or entity names in examples.

## Related References

- [`references/tests/Feature/Http/Controllers/README.md`](../README.md)
