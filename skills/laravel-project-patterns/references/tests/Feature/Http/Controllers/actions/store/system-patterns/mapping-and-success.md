# Store Mapping And Successful Paths

## When To Use

Read this focused reference when the store mapping and successful paths contract is in scope.

## Pattern

- Mock the action, assert parent or child identity and minimal input mapping,
  and return a persisted model only for redirect route generation.
- Primary success may use a required-only payload. Optional/default behavior
  belongs in action integration tests unless the controller owns it.

## Related References

- [Parent subrouter](../system-patterns.md)
