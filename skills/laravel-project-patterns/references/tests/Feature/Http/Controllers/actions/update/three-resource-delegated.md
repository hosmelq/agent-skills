# Three-Resource Delegated Update Tests

## Purpose

Route this controller action and route depth to only the boundary family in
scope; the complete original scenario union remains in the leaves below.

## When To Use

Read this reference for a nested three-resource update route that delegates persistence to an action.

## Required Pattern

### Three-Resource Route Chain (`workspaces.parent-records.children.update`)

- [`three-resource-delegated/access-and-parent-boundaries.md`](three-resource-delegated/access-and-parent-boundaries.md): Access And Parent Boundaries.
- [`three-resource-delegated/child-boundaries.md`](three-resource-delegated/child-boundaries.md): Child Boundaries.
- [`three-resource-delegated/delegated-success.md`](three-resource-delegated/delegated-success.md): Delegated Success.

## Coverage Expectations

Select every leaf affected by the route, binding, policy, response, or
delegated-action change. Do not treat action coverage as a replacement for
these HTTP entrypoint contracts.

## Do Not

- Do not load unrelated boundary families.
- Do not delete a distinct HTTP case because an action test exists.

## Related References

- [`../update.md`](../update.md)
