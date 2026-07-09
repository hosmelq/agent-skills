# Four-Resource Create Tests

## Purpose

Route this controller action and route depth to only the boundary family in
scope; the complete original scenario union remains in the leaves below.

## When To Use

Read this reference for a four-resource `create` route or when auditing the matching nested binding depth.

## Required Pattern

### Four-Resource Route Chain (`workspaces.parent-records.children.leaves.create`)

- [`four-resource/access-and-parent-boundaries.md`](four-resource/access-and-parent-boundaries.md): Access And Parent Boundaries.
- [`four-resource/child-boundaries.md`](four-resource/child-boundaries.md): Child Boundaries.
- [`four-resource/lifecycle-and-success.md`](four-resource/lifecycle-and-success.md): Lifecycle And Success.

## Coverage Expectations

Select every leaf affected by the route, binding, policy, response, or
delegated-action change. Do not treat action coverage as a replacement for
these HTTP entrypoint contracts.

## Do Not

- Do not load unrelated boundary families.
- Do not delete a distinct HTTP case because an action test exists.

## Related References

- [`../create.md`](../create.md)

