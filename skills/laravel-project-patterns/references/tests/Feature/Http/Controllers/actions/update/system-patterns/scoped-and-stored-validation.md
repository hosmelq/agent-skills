# Update Scoped And Stored Validation

## When To Use

Read this focused reference when the update scoped and stored validation contract is in scope.

## Pattern

- Scoped uniqueness covers same-scope duplicates, current-record ignore,
  allowed cross-scope reuse, permitted soft-deleted reuse, and reserved inactive
  rows.
- Stored-value comparisons cover failures and positive omitted or open-ended
  values when the request owns them.

## Related References

- [Parent subrouter](../system-patterns.md)
