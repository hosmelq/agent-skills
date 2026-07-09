# Action Integration Coverage Guide

## When To Use

Use after selecting the live action's matching patterns and scenario family.

## Pattern

For generator or coordinator actions, cover:

- the main success case;
- cleanup of previous active records;
- retry until a valid result exists;
- rejection of already-used or invalid candidates;
- owner-scoped uniqueness, normalized collisions, inactive reservations,
  soft-delete reuse, and cross-owner reuse when applicable;
- maximum-attempt exceptions and exact contract messages.

Also apply only the relevant boundaries:

- Read the live action and equivalent siblings with the same precondition,
  operation, ownership scope, and outcome; do not add behavior by symmetry.
- When active/default scopes affect selection, cover ignored deactivated and
  soft-deleted candidates.
- Data inputs cover full persistence, `Optional` omission, explicit `null`
  clearing, returned identity, and owned side effects.
- Owner isolation belongs here only when the owner is an independent business
  input. Route ownership belongs to the entry point.
- Action-owned mapped exceptions require both action guard coverage and a
  controller mock of the public mapping; do not duplicate the guard in a Form
  Request.
- Range, dependency, and lifecycle guards cover the applicable scope,
  soft-delete, adjacency/open-ended, dependency-state, and exact-exception
  branches. Add database constraints when PostgreSQL can express the invariant.
- A guard moved from a request because it needs fresh or dependent state is
  action-owned; controller coverage still proves validation, invocation, and
  the public response.

## Related References

- [Parent router](README.md)
- [Scenario catalog](scenario-catalog.md)
