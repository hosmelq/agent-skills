# tests/Integration/Actions

## Purpose

This reference defines conventions for action integration tests under `tests/Integration/Actions`.

## When To Use

Use this reference when your task matches this path and you need to follow its local conventions.

## Required Pattern

Use `tests/Integration/Actions/<Action>Test.php` for action classes that coordinate persistence, transactions, retries, external collaborators, or domain exceptions.

Load [`scenario-catalog.md`](scenario-catalog.md) before assembling a suite
from equivalent actions that share the same precondition, operation, ownership
scope, and outcome.
It is the authoritative deduplicated operation union and preserves the verified
case order; the examples below show the implementation shape for those rows.

### Reference Map

- [`patterns/file-shape.md`](patterns/file-shape.md): File Shape.
- [`patterns/mocking-collaborators.md`](patterns/mocking-collaborators.md): Mocking Collaborators.
- [`patterns/generated-identifier-scope-examples.md`](patterns/generated-identifier-scope-examples.md): Generated Identifier Scope Examples.
- [`patterns/data-input-actions.md`](patterns/data-input-actions.md): Data Input Actions.
- [`patterns/action-owned-guard-overview.md`](patterns/action-owned-guard-overview.md): Action-Owned Guard Overview.
- [`patterns/top-level-update.md`](patterns/top-level-update.md): Top-Level Update.
- [`patterns/lifecycle-mutation.md`](patterns/lifecycle-mutation.md): Lifecycle Mutation.
- [`patterns/scoped-bulk-update.md`](patterns/scoped-bulk-update.md): Scoped Bulk Update.
- [`patterns/dependent-record-guards.md`](patterns/dependent-record-guards.md): Dependent-Record Guards.
- [`patterns/branch-specific-domain-exceptions.md`](patterns/branch-specific-domain-exceptions.md): Branch-Specific Domain Exceptions.
- [`patterns/range-guards.md`](patterns/range-guards.md): Range Guards.
- [`patterns/owner-lifecycle-guards-for-nested-mutations.md`](patterns/owner-lifecycle-guards-for-nested-mutations.md): Owner Lifecycle Guards For Nested Mutations.
- [`patterns/model-targeted-mutations.md`](patterns/model-targeted-mutations.md): Model-Targeted Mutations.
- [`patterns/range-update-guards.md`](patterns/range-update-guards.md): Range Update Guards.
- [`patterns/framework-contract-actions.md`](patterns/framework-contract-actions.md): Framework Contract Actions.

## Coverage Expectations

Select only the applicable rows from
[`coverage-guide.md`](coverage-guide.md) and the scenario catalog. Do not add
symmetry cases for behavior the live action does not own.

## Do Not

- Do not test action behavior only through a controller if the action has meaningful branching.
- Do not make live external calls; use mocks or HTTP fakes.
- Do not contradict the skill non-negotiables or project conventions.

## Related References

- [`references/tests/Integration/Actions/scenario-catalog.md`](scenario-catalog.md)
- [`references/app/Actions/README.md`](../../../app/Actions/README.md)
- [`references/app/Exceptions/README.md`](../../../app/Exceptions/README.md)
