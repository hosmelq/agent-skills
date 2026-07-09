# Shared Login Helper

## When To Use

Use this leaf for authenticated test setup through the shared login helper.

## Pattern

### Shared Login Helper

Use `login()` instead of hand-writing `actingAs()` in feature and integration tests that need an authenticated actor.

- `login()` creates an actor when none is provided.
- `login(workspace: $workspace)` creates an actor with that `Workspace` relationship when needed.
- `login($actor)` authenticates a specific existing actor.
- The helper returns the authenticated `Actor`, so keep it when assertions need the actor's public id or `Workspace` state.
- For controller validation tests, pass an authorized in-scope `Workspace` to `login(...)` so validation runs instead of being hidden by authorization or binding failures.

## Related References

- [Parent router](../Pest.md)
