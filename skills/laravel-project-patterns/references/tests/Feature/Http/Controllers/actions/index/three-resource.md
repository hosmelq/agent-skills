# Three-Resource Index Tests

## Purpose

Route this controller action and route depth to only the boundary family in
scope; the complete original scenario union remains in the leaves below.

## When To Use

Read this reference for a three-resource `index` route or when auditing the matching nested binding depth.

## Required Pattern

### Three-Resource Route Chain (`workspaces.parent-records.children.index`)

- [`three-resource/access-and-binding.md`](three-resource/access-and-binding.md): Access And Binding.
- [`three-resource/listing-and-exclusions.md`](three-resource/listing-and-exclusions.md): Listing And Exclusions.

## Coverage Expectations

Select every leaf affected by the route, binding, policy, response, or
delegated-action change. Do not treat action coverage as a replacement for
these HTTP entrypoint contracts.

## Do Not

- Do not load unrelated boundary families.
- Do not delete a distinct HTTP case because an action test exists.

## Related References

- [`../index.md`](../index.md)
