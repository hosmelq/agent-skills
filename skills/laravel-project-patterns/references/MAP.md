# Reference Navigation Map

## Purpose

Provide the compact navigation tree for every reference domain without forcing an agent to load the complete reference catalog.

## When To Use

Read this map when the touched project path is known but the matching router is
not. After selecting a router, follow only that branch to its focused leaf.

## Required Pattern

Navigate progressively:

1. Match the changed project path to one top-level router below.
2. Read that router's applicability guidance and reference map.
3. Select only the relevant subrouter, action, mode, pattern, or validation family.
4. Stop at the focused leaf; do not preload sibling leaves.

### Focused References

- [Core Contract Map](maps/core.md): Use this map to select one cross-cutting contract.
- [Project And Application Map](maps/project-and-application.md): Use this map to select one project or application-layer router.
- [Database And Resource Map](maps/database-and-resources.md): Use this map to select one database or resource router.
- [Test Map](maps/tests.md): Use this map to select one test suite or test-support router.

## Coverage Expectations

Every reference must remain reachable through `SKILL.md`, this map, and the
applicable domain routers. Dense branches may add submaps, but every submap must
explain the decision that selects each child. Keep the navigation chain short
and preserve the focused examples in their leaves.

## Do Not

- Do not turn this map into a flat inventory of every leaf.
- Do not duplicate examples, datasets, or pattern rules here.
- Do not load every linked router for one task.
- Do not add an unlinked reference or a wildcard branch without an owning
  router that enumerates its leaves.

## Related References

- [`SKILL.md`](../SKILL.md)
- [`references/README.md`](README.md)
