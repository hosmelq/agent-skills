# Store Range Creation

## When To Use

Read this focused reference when the store range creation contract is in scope.

## Pattern

- Range/overlap validation stays in the Form Request only when request-owned.
  Guards needing transactional reads or locks belong in action integration
  tests; controller tests keep exception mapping.
- Preserve controller examples for overlap and second open-ended exceptions,
  base-validation short-circuiting, and successful open-ended mapping when
  those HTTP contracts exist.

## Related References

- [Parent subrouter](../system-patterns.md)
