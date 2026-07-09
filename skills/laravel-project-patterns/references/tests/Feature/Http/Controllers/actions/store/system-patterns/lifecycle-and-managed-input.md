# Store Lifecycle And Managed Input

## When To Use

Read this focused reference when the store lifecycle and managed input contract is in scope.

## Pattern

- Lifecycle guards that stop before validation send minimal payloads and assert
  the action was not called.
- Server-managed fields stay out of request rules unless the endpoint exposes a
  `missing` or `prohibited` contract.

## Related References

- [Parent subrouter](../system-patterns.md)
