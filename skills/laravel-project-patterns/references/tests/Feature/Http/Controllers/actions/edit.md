# Edit Action Templates

## Purpose

This reference defines `describe('edit')` patterns for controller feature tests.

## When To Use

Use this reference when a web/session controller exposes an edit page. For JSON endpoints, keep the same boundary order and adapt assertions with `../modes/api-json.md`.

## Required Pattern

Apply the [shared actor context](README.md#shared-actor-context).

For three- and four-resource chains, prepend the full member binding order from `../route-patterns.md`: authentication, unrelated Workspace authorization, ancestor and parent `404` cases, child `404` cases, leaf wrong parent, leaf wrong ancestor graph, leaf wrong Workspace, leaf soft-deleted, then lifecycle/edit-page contract.

### Reference Map

- [`edit/two-resource.md`](edit/two-resource.md): Two-Resource Route Chain (`workspaces.parent-records.edit`).
- [`edit/three-resource.md`](edit/three-resource.md): Three-Resource Route Chain (`workspaces.parent-records.children.edit`).
- [`edit/four-resource.md`](edit/four-resource.md): Four-Resource Route Chain (`workspaces.parent-records.children.leaves.edit`).

### System Edit Patterns

- Edit pages with derived state must assert that prop in both false and true states when the form relies on it.
- Edit pages with enum or reference-data options assert those props along with every ancestor public ID.
- Edit pages with dependent options assert the stored value in the full response and `reloadOnly(...)` for refreshes.
- Deep edit pages follow the full leaf boundary order before lifecycle guards and the response contract.

## Coverage Expectations

Use the live controller, routes, form requests, policies, resources, and
equivalent live siblings with the same route shape and response contract to
decide the complete matrix. Preserve examples, but keep them synthetic and
only implement applicable cases in PHP.

## Do Not

- Do not contradict the skill non-negotiables or project conventions.
- Do not drop applicable controller boundary coverage when adapting examples.
- Do not use real module, route, or entity names in examples.

## Related References

- [`references/tests/Feature/Http/Controllers/README.md`](../README.md)
