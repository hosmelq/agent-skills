# Delegated Mutation Boundaries

## When To Use

Use this leaf for mock inputs, return values, deduplication, and surviving HTTP contracts.

## Pattern

### Delegated Mutation Boundaries

- Mocked actions receive only the models and inputs that are business inputs to the operation. Do not pass route hierarchy only to re-check ownership already enforced by scoped bindings and policy.
- `withArgs(...)` callbacks must return booleans. Do not put Pest `expect()` chains inside Mockery matchers.
- For create/store mocks that need a redirect route key, return a persisted factory model with only required relationships. Do not set generated route keys unless the literal value is asserted.
- Add `andReturn(...)` only when the controller consumes the mocked action result. Do not stub an unused return for updates, deletes, lifecycle mutations, or redirects built from an already-bound model.
- For partial update mocks, assert submitted fields and request-normalized fields only where relevant. Defaults, `Optional`, nullable clearing, and side effects belong in action integration tests unless the controller owns them.
- For new coverage, successful cases may share one minimal controller mapping
  case only after a full-equivalence audit proves identical route, bound-model
  state, middleware, authorization, request validation, mapping, action call,
  redirect, toast, and side effects. Move only action-internal persistence
  branches to action integration tests.
- Model state is not automatically an action-owned difference. Keep a separate controller case when it changes binding, authorization, middleware, request validation, public-ID resolution, action reachability, or another observable HTTP outcome. That case may be a success, `403`, `404`, or validation failure. A soft-deleted route model is normally a `404`; require a success only when the live route explicitly accepts trashed models.
- Do not delete the final successful controller case for a distinct accepted HTTP path. Validation failures and action tests that construct the Data input or model directly do not prove that a valid request and its bound-model state reach the action.

Before deleting an existing controller case as duplicate:

1. confirm the scenario is reachable from the live route, controller, request, policy, resource, or existing tests;
2. load the path-matched action or pattern reference only for that applicable behavior;
3. identify the surviving controller case for each affected binding, authorization, validation, mapping, invocation, and response contract;
4. compare exception factory, validation field/message, status, redirect,
   toast, and side effects exactly;
5. keep the case when it proves a distinct accepted or rejected HTTP path, or
   when no named surviving case proves the same payload plus bound-model state
   reaches the mocked action.

A passing action integration test is never evidence for steps 3–4 because it
bypasses the HTTP entry point.

## Related References

- [Parent router](../delegated-action-contracts.md)
