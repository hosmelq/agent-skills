# Three-Resource Store Tests

## Purpose

Route this controller action and route depth to only the boundary family in
scope; the complete original scenario union remains in the leaves below.

## When To Use

Read this reference for a three-resource `store` route or when auditing the matching nested binding depth.

## Required Pattern

### Three-Resource Route Chain (`workspaces.parent-records.children.store`)

- [`three-resource/access-and-binding.md`](three-resource/access-and-binding.md): Access And Binding.
- [`three-resource/request-validation.md`](three-resource/request-validation.md): Request Validation.
- [`three-resource/delegated-success.md`](three-resource/delegated-success.md): Delegated Success.

## Coverage Expectations

Select every leaf affected by the route, binding, policy, response, or
delegated-action change. Do not treat action coverage as a replacement for
these HTTP entrypoint contracts.

## Do Not

- Do not load unrelated boundary families.
- Do not delete a distinct HTTP case because an action test exists.

## Related References

- [`../store.md`](../store.md)
