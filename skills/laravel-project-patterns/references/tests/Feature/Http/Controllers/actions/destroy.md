# Destroy Action Templates

## Purpose

This reference defines `describe('destroy')` patterns for controller feature tests.

## When To Use

Use this reference when a web/session controller exposes a `destroy` action. For delegated destroy actions, keep controller tests at the HTTP boundary and move persistence/transactions to action integration tests.

## Required Pattern

Apply the [shared actor context](README.md#shared-actor-context).

For three- and four-resource chains, prepend the full member binding order from `../route-patterns.md`: authentication, unrelated Workspace authorization, ancestor and parent `404` cases, child `404` cases, leaf wrong parent, leaf wrong ancestor graph, leaf wrong Workspace, leaf soft-deleted, then lifecycle/delete guard or success.

If deletion has domain preconditions, place those tests after binding/soft-delete coverage and before success.

### Reference Map

- [`destroy/two-resource.md`](destroy/two-resource.md): Two-Resource Route Chain (`workspaces.parent-records.destroy`).
- [`destroy/three-resource.md`](destroy/three-resource.md): Three-Resource Route Chain (`workspaces.parent-records.children.destroy`).
- [`destroy/four-resource.md`](destroy/four-resource.md): Four-Resource Route Chain (`workspaces.parent-records.children.leaves.destroy`).
- [`destroy/delegated-domain-rejection.md`](destroy/delegated-domain-rejection.md): Delegated Destroy Domain Rejection.

### System Destroy Patterns

- Mock the delegated destroy action and assert bound models, redirect/toast, and exception-to-validation mapping.
- Do not add deletion-state assertions to delegated controller tests; action integration tests own state, locks, transactions, and complete guard matrices.
- For new coverage, one mapping case may represent several action-internal
  dependency states only after proving that the route, bound-model state,
  middleware, authorization, request, action call, exception factory, field,
  message, status, redirect, toast, and side effects are all identical.
- This is not permission to delete an existing controller case. Deletion
  requires an explicit full-equivalence audit and a named surviving case for
  every HTTP contract. Action integration coverage alone never proves that
  equivalence. Do not create fixtures the mocked action cannot inspect.
- If policy or lifecycle state stops before the action, assert the response and that the action was not called.

## Coverage Expectations

Use the live controller, routes, policies, actions, and equivalent live siblings
with the same route shape and mutation ownership to decide the complete destroy
matrix. Domain failure cases should assert HTTP validation errors and should
not delete the record.

## Do Not

- Do not contradict the skill non-negotiables or project conventions.
- Do not drop applicable controller boundary coverage when adapting examples.
- Do not use real module, route, or entity names in examples.

## Related References

- [`references/tests/Feature/Http/Controllers/README.md`](../README.md)
