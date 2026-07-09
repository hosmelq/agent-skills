# Update Lifecycle And Action Guards

## When To Use

Read this focused reference when the update lifecycle and action guards contract is in scope.

## Pattern

- Lifecycle guards that stop before validation send minimal data and assert the
  action was not called.
- Request-owned dependent-record restrictions may stay in Form Requests. Guards
  needing transactional reads or locks belong in action integration tests,
  while controller tests keep the mapped validation contract.

## Related References

- [Parent subrouter](../system-patterns.md)
