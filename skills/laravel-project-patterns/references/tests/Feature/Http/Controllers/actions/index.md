# Index Action Templates

## Purpose

This reference defines `describe('index')` patterns for controller feature tests.

## When To Use

Use this reference when a web/session controller exposes an index/list page. For JSON list endpoints, keep the same scoping matrix and adapt assertions with `../modes/api-json.md`.

## Required Pattern

Apply the [shared actor context](README.md#shared-actor-context).

For three- and four-resource chains, prepend the full collection binding order from `../route-patterns.md`: authentication, unrelated Workspace authorization, outer parent wrong Workspace, outer parent soft-deleted, child wrong parent in the same Workspace, child wrong Workspace, child soft-deleted, then lifecycle/list contract.

### Reference Map

- [`index/two-resource.md`](index/two-resource.md): Two-Resource Route Chain (`workspaces.parent-records.index`).
- [`index/three-resource.md`](index/three-resource.md): Three-Resource Route Chain (`workspaces.parent-records.children.index`).
- [`index/four-resource.md`](index/four-resource.md): Four-Resource Route Chain (`workspaces.parent-records.children.leaves.index`).

### Exclusion Variants

Add list exclusion cases for every scope that can leak records:

- other Workspace;
- other direct parent in the same Workspace;
- deeper ancestor mismatch when the route has more than one parent;
- same direct parent with mismatched redundant `Workspace` or ancestor ownership;
- soft-deleted rows when the listed model uses soft deletes.

## Coverage Expectations

For nested index actions, include both successful listing context props and negative list-exclusion cases. The included collection count and excluded public IDs should be asserted in the same response contract when practical.

## Do Not

- Do not contradict the skill non-negotiables or project conventions.
- Do not drop applicable controller boundary coverage when adapting examples.
- Do not use real module, route, or entity names in examples.

## Related References

- [`references/tests/Feature/Http/Controllers/README.md`](../README.md)
