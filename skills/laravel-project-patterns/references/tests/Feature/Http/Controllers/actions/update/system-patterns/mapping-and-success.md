# Update Mapping And Successful Paths

## When To Use

Read this focused reference when the update mapping and successful paths contract is in scope.

## Pattern

- Mock the action, assert target identity, and verify minimal input mapping.
  Include an owner only when it is a genuine business input.
- Keep partial updates partial; do not add every input property.
- For new coverage, share one success only after a full-equivalence audit proves
  the route, bound-model state, authorization, validation, mapped input, action
  call, redirect, toast, and side effects are identical. Never delete an
  existing success merely because an action test covers the internal state.
- Preserve a success for every distinct accepted HTTP path; direct action tests
  do not prove valid request mapping.
- Action-owned omission, `Optional`, `null` clearing, side effects, and
  persistence belong in action integration tests.

## Related References

- [Parent subrouter](../system-patterns.md)
