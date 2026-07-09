# app/Actions

## Purpose

Route action work to the smallest applicable behavior leaves.

## When To Use

Use for action classes, action inputs, generated values, lifecycle transitions,
coordinated writes, ordering, or action-owned tests.

## Required Pattern

### Reference Map

- [`patterns/action-shape.md`](patterns/action-shape.md): Action Shape.
- [`patterns/framework-contract-actions.md`](patterns/framework-contract-actions.md): Framework Contract Actions.
- [`patterns/action-inputs.md`](patterns/action-inputs.md): Action Inputs.
- [`patterns/create-and-child-mutations.md`](patterns/create-and-child-mutations.md): Create And Child Mutations.
- [`patterns/coordinated-writes.md`](patterns/coordinated-writes.md): Coordinated Writes.
- [`patterns/composed-scoped-create.md`](patterns/composed-scoped-create.md): Composed Scoped Create.
- [`patterns/aggregate-relation-resolution.md`](patterns/aggregate-relation-resolution.md): Aggregate relation suggestions, independent confirmation, and effective persisted-plus-submitted values.
- [`patterns/aggregate-nested-writes-and-rollback.md`](patterns/aggregate-nested-writes-and-rollback.md): Atomic root-and-child writes with pre-resolved relations and full rollback.
- [`patterns/named-constraint-exception-mapping.md`](patterns/named-constraint-exception-mapping.md): Map only an explicitly named database constraint race to its domain exception.
- [`patterns/existing-shared-root-lock-protocols-only.md`](patterns/existing-shared-root-lock-protocols-only.md): Existing Shared-Root Lock Protocols Only.
- [`patterns/data-inputs.md`](patterns/data-inputs.md): Data Inputs.
- [`patterns/generated-values-and-resolvers.md`](patterns/generated-values-and-resolvers.md): Generated Values And Resolvers.
- [`patterns/exclusive-default-selection.md`](patterns/exclusive-default-selection.md): Exclusive Default Selection.
- [`patterns/ensure-exclusive-state.md`](patterns/ensure-exclusive-state.md): Idempotently find, promote, or create one active eligible selection.
- [`patterns/group-order-transitions.md`](patterns/group-order-transitions.md): Move within a group or normalize source/destination groups after a category change.
- [`patterns/idempotent-lifecycle-flags.md`](patterns/idempotent-lifecycle-flags.md): Preserve deactivation timestamps and reset distinguished flags on reactivation.
- [`patterns/test-mapping.md`](patterns/test-mapping.md): Test Mapping.

## Coverage Expectations

Read the live action and equivalent actions with the same precondition,
operation, and outcome. Cover action-owned behavior in the owning suite without
removing entrypoint coverage or adding adjacent cases for symmetry.

## Do Not

- Do not contradict the skill non-negotiables or project conventions.
- Do not introduce `lockForUpdate()` from similarity to another module; prove the full competing-action protocol first.
- Do not replace an inapplicable lock suggestion with conditional writes, affected-row checks, re-queries, retries, or another concurrency mechanism unless the live action already owns a separately evidenced contract for that mechanism.

## Related References

- [`references/app/README.md`](../README.md)
- [`references/tests/Integration/Actions/README.md`](../../tests/Integration/Actions/README.md)
