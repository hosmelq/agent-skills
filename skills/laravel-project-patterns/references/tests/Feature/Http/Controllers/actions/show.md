# Show Action Templates

## Purpose

This reference defines `describe('show')` patterns for controller feature tests.

## When To Use

Use this reference when a web/session controller exposes a show page. For JSON show endpoints, keep the same binding matrix and adapt assertions with `../modes/api-json.md`.

## Required Pattern

Apply the [shared actor context](README.md#shared-actor-context).

For three- and four-resource chains, prepend the full member binding order from `../route-patterns.md`: authentication, unrelated Workspace authorization, ancestor and parent `404` cases, child `404` cases, leaf wrong parent, leaf wrong ancestor graph, leaf wrong Workspace, leaf soft-deleted, then lifecycle/read-continuity or show contract.

### Reference Map

- [`show/two-resource.md`](show/two-resource.md): Two-Resource Route Chain (`workspaces.parent-records.show`).
- [`show/three-resource.md`](show/three-resource.md): Three-Resource Route Chain (`workspaces.parent-records.children.show`).
- [`show/four-resource.md`](show/four-resource.md): Four-Resource Route Chain (`workspaces.parent-records.children.leaves.show`).

### System Show Patterns

- Show pages assert the component and public ID for the shown resource.
- Nested show pages assert every ancestor public ID used by links, breadcrumbs, or child actions.
- Related or derived resources are part of the response contract when the controller always returns them.
- Soft-deleted leaves return `404` before response-contract assertions.
- Read-continuity variants stay as success tests only when the live policy allows them.

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
