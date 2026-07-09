# Controller Action Test Map

## Purpose

Route a web controller test to the action and nesting-depth examples that
match its route.

## When To Use

Load this map after the controller-test router. Select the action first, then
read only the route-depth or ownership leaves linked by that action.

## Required Pattern

- [`create.md`](create.md): create-page access, scoped bindings, options, and
  Inertia page props.
- [`store.md`](store.md): store access, request validation, delegated/direct
  success, redirect, and toast.
- [`index.md`](index.md): list access, exclusions, ordering, and Inertia props.
- [`show.md`](show.md): bound-record access, stored unavailable relations, and
  page props.
- [`edit.md`](edit.md): edit access, dependent options, stored unavailable
  relations, and page props.
- [`update.md`](update.md): update access, stored-state validation,
  request-to-input mapping, range branches, and success.
- [`destroy.md`](destroy.md): delete access, dependency failures,
  delegated/direct success, redirect, and toast.

Each action router selects two-, three-, or four-resource nesting. Read deeper
binding, request, range, or lifecycle leaves only when that router links them
for the route shape.

### Shared Actor Context

- `assertForbidden()` uses an authenticated actor whose request resolves
  bindings but is not authorized for the route `Workspace`.
- Validation and success cases use an actor authorized for that `Workspace`.
- `assertNotFound()` binding cases may use any authenticated actor unless the
  route uses a policy-masked `404`.

## Coverage Expectations

Preserve the action-specific failure-to-success order and every scoped binding
boundary applicable to the selected route depth.

## Do Not

- Do not load every action for a one-action change.
- Do not infer that a shallower route lacks a case merely because the deepest
  route has more ownership boundaries.
- Do not collapse direct and delegated mutation examples.

## Related References

- [`../README.md`](../README.md)
- [`../route-patterns.md`](../route-patterns.md)
- [`../pattern-catalog.md`](../pattern-catalog.md)
