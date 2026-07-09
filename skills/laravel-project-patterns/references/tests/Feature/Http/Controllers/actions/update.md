# Update Action Templates

## Purpose

This reference defines `describe('update')` patterns for controller feature tests.

## When To Use

Use this reference when a web/session controller exposes an `update` action. For JSON endpoints, keep the same validation and side-effect discipline and adapt assertions with `../modes/api-json.md`.

## Required Pattern

The update controller test always proves the HTTP entry point. Mock the delegated Data input-backed action and assert route-bound model identity, request-to-input mapping, redirect/toast, and exception-to-validation mapping. Persistence, nullable clearing, transactions, locks, and dependent-record guards belong in `tests/Integration/Actions`. Assert `Optional` in the controller only when omission itself is the distinct accepted HTTP input path.

Apply the [shared actor context](README.md#shared-actor-context).

For three- and four-resource chains, prepend the full member binding order from `../route-patterns.md`: authentication, unrelated Workspace authorization, ancestor and parent `404` cases, child `404` cases, leaf wrong parent, leaf wrong ancestor graph, leaf wrong Workspace, leaf soft-deleted, then lifecycle, validation, mapped action exceptions, and success.

After the base validation dataset, add named tests for scoped uniqueness, stored-value comparisons, nullable relationship clearing, request-owned dependent-record rules, and mapped action exceptions.

### Reference Map

- [`update/two-resource-delegated.md`](update/two-resource-delegated.md): Two-Resource Route Chain (`workspaces.parent-records.update`).
- [`update/three-resource-delegated.md`](update/three-resource-delegated.md): Three-Resource Route Chain (`workspaces.parent-records.children.update`).
- [`update/four-resource-delegated.md`](update/four-resource-delegated.md): Four-Resource Route Chain (`workspaces.parent-records.children.leaves.update`).
- [`update/stored-bound-validation.md`](update/stored-bound-validation.md): Stored-Bound Validation Example.
- [`update/range-open-ended.md`](update/range-open-ended.md): Delegated Range / Open-Ended Update Examples.

### Additional Validation References

Load focused validation files before the broader catalog:

- [`references/tests/Feature/Http/Controllers/validation/required-with-and-array.md`](../validation/required-with-and-array.md)
- [`references/tests/Feature/Http/Controllers/validation/scoped-exists-and-unique.md`](../validation/scoped-exists-and-unique.md)
- [`references/tests/Feature/Http/Controllers/validation/prepare-for-validation.md`](../validation/prepare-for-validation.md)
- [`references/tests/Feature/Http/Controllers/validation/update-validates-fields.md`](../validation/update-validates-fields.md)

### Focused References

- [System Update Patterns](update/system-patterns.md): Use this leaf to select distinct delegated update and validation scenarios.

## Coverage Expectations

Use the live controller, routes, form requests, policies, resources, actions,
and equivalent live siblings with the same route shape, request rules, and
mutation ownership to decide the complete update matrix. Named tests are
preferred for rules that depend on stored model values or related records.

## Do Not

- Do not contradict the skill non-negotiables or project conventions.
- Do not drop applicable controller boundary coverage when adapting examples.
- Do not use real module, route, or entity names in examples.

## Related References

- [`references/tests/Feature/Http/Controllers/README.md`](../README.md)
