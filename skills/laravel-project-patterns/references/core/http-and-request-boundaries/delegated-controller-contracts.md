# Delegated Controller Contracts

## When To Use

Use for a controller mutation that delegates to a Data input-backed action.

## Pattern

- Mock the action and assert the HTTP boundary plus scenario-relevant
  Request-to-Input mapping. Required-only store payloads may map only required
  input. Keep partial updates partial. Persistence, defaults, nullable clearing,
  and side effects belong in `tests/Integration/Actions`.
- Collapse successes only when input fields, accepted bound-model state, action,
  and response are identical and only action-owned state differs. Keep cases
  where state changes binding, authorization, middleware, request acceptance,
  public-ID resolution, action reachability, or another HTTP outcome. Preserve
  the last success for every distinct accepted HTTP path.
- Before deleting a case, prove the scenario is reachable and identify the
  surviving case for every affected HTTP boundary. If no case proves the same
  payload and bound state reach the mocked action and response, keep it.
- Controllers remain tested entry points even when action tests cover internal
  guards. Preserve authentication, authorization, binding, validation,
  invocation, mapping, response, and exception-mapping evidence.
- For destroy or lifecycle actions, pass the bound model and preserve every
  distinct public exception mapping. Equivalent dependency states may share one
  mapping case while the action suite proves their state matrix.
- A mocked store action returns a persisted `createOne()` model only when the
  controller consumes its route key. Set no generated key or irrelevant
  attribute. Configure `andReturn(...)` only when consumed.
- A mocked exception proves only HTTP mapping; the action integration test
  proves the guard and persisted result.

## Related References

- [Parent router](../http-and-request-boundaries.md)
- [`delegated-action-contracts.md`](../../tests/Feature/Http/Controllers/delegated-action-contracts.md)
