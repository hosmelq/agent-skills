# Controller Entrypoint Contracts

## When To Use

Use this leaf before deciding which routed HTTP cases must remain in a
controller feature test.

## Pattern

- Controller feature tests are entry-point tests. They remain required for authentication, authorization, scoped binding, request validation, action invocation, request-to-input mapping, redirects/toasts, Inertia/JSON contracts, and exception-to-validation mapping.
- Action integration coverage does not replace controller entry-point coverage. If a routed controller can reach a boundary, guard, validation path, mapped action exception, redirect, toast, or response contract, keep the controller test for that entry point even when the same domain condition is fully proven in `tests/Integration/Actions`.
- Form Requests own HTTP input shape, request normalization, scoped `exists`/`unique`, and request-safe cross-field validation.
- Actions own transactional state, dependent-record checks, race-sensitive domain guards, and any transaction or lock that the live contract actually requires.
- When the controller catches an action exception and maps it to validation, the controller test mocks the action and asserts the mapped validation error. The matching action integration test owns the real guard and persistence behavior, plus transaction or lock coverage only when those mechanisms are part of the live action contract.
- Preserve one controller case for every distinct observable HTTP contract. For
  new coverage, several action-internal preconditions may share one mapping
  case only after a full-equivalence audit proves identical route, bound-model
  state, middleware, authorization, request validation, action call, exception
  factory, field, message, status, redirect, toast, and side effects. This is
  never permission to delete an existing case merely because action coverage
  exists; deletion requires naming the surviving equivalent controller test.
- Name the test after the observable rejected behavior with `rejects` or `prevents`, not after the controller's exception-mapping implementation.
- Inertia assertions are backend response contracts: component names, serialized props, public IDs, redirects, and flash/toast output. They are not browser behavior tests.
- API assertions must assert exact validation messages and the JSON contract, not just status codes.
- Route parameters should use the public route key shape exposed by route binding. Internal integer IDs belong only at persistence assertions after validation or controller resolution.
- Assign every request-helper result to `$response`, then assert on `$response` in a separate statement. This keeps request construction distinct from the observable HTTP contract.

## Related References

- [`README.md`](README.md)
- [`delegated-action-contracts.md`](delegated-action-contracts.md)
- [`references/core/http-and-request-boundaries.md`](../../../../core/http-and-request-boundaries.md)
