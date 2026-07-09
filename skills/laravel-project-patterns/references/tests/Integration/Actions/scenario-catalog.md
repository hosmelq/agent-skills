# Action Integration Scenario Catalog

## Purpose

This is the deduplicated semantic union of action integration-test variants.
Each row represents one distinct test. Comparable modules contribute cases to
the same ordered operation catalog; cases are merged only when precondition,
input, outcome, persistence effect, and ownership scope are equivalent.

Use this catalog with `README.md`. The README supplies complete Pest examples;
this file prevents less common variants from disappearing when a new suite is
assembled from several equivalent siblings.

## When To Use

Load this file after `README.md` whenever an action suite is created, expanded,
or compared with equivalent sibling modules that share the same precondition,
operation, ownership scope, and outcome. Select every row whose precondition
and owned behavior exist in the action; do not copy rows for behavior the
action does not own.

This catalog is a coverage inventory, not an implementation template. An action
category or nearby row never authorizes adding behavior to source code. For
conditional lock rows, "owned behavior" means the live action already
participates in a documented shared-root protocol with every competing action;
never infer a new lock from this catalog.

## Required Pattern

### Reference Map

- [`scenario-catalog/ordering-rule.md`](scenario-catalog/ordering-rule.md): Ordering Rule.
- [`scenario-catalog/create-actions.md`](scenario-catalog/create-actions.md): Create Actions.
- [`scenario-catalog/aggregate-create-actions.md`](scenario-catalog/aggregate-create-actions.md): aggregate creation, nested rollback, relation confirmation, defaults, and named constraint races.
- [`scenario-catalog/aggregate-update-actions.md`](scenario-catalog/aggregate-update-actions.md): historical-current relation continuity, effective-value validation, partial updates, and named constraint races.
- [`scenario-catalog/aggregate-delete-and-child-actions.md`](scenario-catalog/aggregate-delete-and-child-actions.md): terminal-state child guards, minimum-child invariants, and atomic aggregate deletion.
- [`scenario-catalog/lifecycle-default-and-delete-actions.md`](scenario-catalog/lifecycle-default-and-delete-actions.md): Lifecycle, Default, and Delete Actions.
- [`scenario-catalog/existing-shared-root-lock-protocol-conditional.md`](scenario-catalog/existing-shared-root-lock-protocol-conditional.md): Existing Shared-Root Lock Protocol (Conditional).
- [`scenario-catalog/generated-values-and-resolvers.md`](scenario-catalog/generated-values-and-resolvers.md): Generated Values and Resolvers.
- [`scenario-catalog/update-actions.md`](scenario-catalog/update-actions.md): Update Actions.
- [`scenario-catalog/state-and-order-actions.md`](scenario-catalog/state-and-order-actions.md): Exclusive-state, lifecycle-flag, direct-move, and ordered-group transition actions.
- [`scenario-catalog/framework-contract-actions.md`](scenario-catalog/framework-contract-actions.md): Framework Contract Actions.

## Coverage Expectations

The applicable rows are the suite checklist. Preserve their order, implement
each as a concrete Pest test, and compare against the live action plus every
equivalent sibling before declaring the suite complete.

## Do Not

- Do not copy one catalog row per production model when the behavior is
  semantically identical.
- Do not collapse rows that differ by active, inactive, soft-deleted, owner,
  omitted, explicit-null, open-ended, or persisted fallback state.
- Do not move route binding, policy, request validation, redirect, or toast
  rows into this catalog; those belong to controller feature tests.
- Do not add `lockForUpdate()` to create, update, move/order, initial/default, or
  lifecycle actions because another module or catalog row uses it. The complete
  shared-root protocol must already be justified by the live invariant and
  every competing action.

## Related References

- [`README.md`](README.md)
- [`references/app/Actions/README.md`](../../../app/Actions/README.md)
- [`references/tests/Feature/Http/Controllers/README.md`](../../Feature/Http/Controllers/README.md)
