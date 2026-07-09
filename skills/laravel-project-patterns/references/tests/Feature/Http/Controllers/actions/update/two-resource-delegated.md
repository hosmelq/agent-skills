# Two-Resource Delegated Update Tests

## Purpose

Route this controller action and route depth to only the boundary family in
scope; the complete original scenario union remains in the leaves below.

## When To Use

Read this reference for a two-resource update route whose controller delegates
the mutation to an action.

## Required Pattern

### Two-Resource Route Chain (`workspaces.parent-records.update`)

- [`two-resource-delegated/access-and-binding.md`](two-resource-delegated/access-and-binding.md): Access And Binding.
- [`two-resource-delegated/request-validation.md`](two-resource-delegated/request-validation.md): Request Validation.
- [`two-resource-delegated/delegated-success.md`](two-resource-delegated/delegated-success.md): Delegated Success.

## Coverage Expectations

Select every leaf affected by the route, binding, policy, response, or
delegated-action change. Do not treat action coverage as a replacement for
these HTTP entrypoint contracts.

## Do Not

- Do not load unrelated boundary families.
- Do not delete a distinct HTTP case because an action test exists.

## Related References

- [`../update.md`](../update.md)
