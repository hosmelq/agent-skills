# Four-Resource Destroy Binding Boundaries

## Purpose

Route this controller action and route depth to only the boundary family in
scope; the complete original scenario union remains in the leaves below.

## When To Use

Read this reference when the task requires four-resource destroy binding boundaries.

## Required Pattern

### Four-Resource Route Chain (`workspaces.parent-records.children.leaves.destroy`)

- [`binding-boundaries/access-and-parent-boundaries.md`](binding-boundaries/access-and-parent-boundaries.md): Access And Parent Boundaries.
- [`binding-boundaries/child-boundaries.md`](binding-boundaries/child-boundaries.md): Child Boundaries.
- [`binding-boundaries/leaf-boundaries.md`](binding-boundaries/leaf-boundaries.md): Leaf Boundaries.

## Coverage Expectations

Select every leaf affected by the route, binding, policy, response, or
delegated-action change. Do not treat action coverage as a replacement for
these HTTP entrypoint contracts.

## Do Not

- Do not load unrelated boundary families.
- Do not delete a distinct HTTP case because an action test exists.

## Related References

- [`../four-resource.md`](../four-resource.md)

