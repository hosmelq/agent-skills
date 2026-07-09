# Access / Order Patterns

## When To Use

Read this focused reference when the task involves access / order patterns.

## Pattern

### Access / Order Patterns

- Web unauthenticated actions redirect to the login route.
- Protected JSON endpoints return `401` for guests.
- Authorization denials are `403` when route bindings resolve.
- Binding failures and scoped chain mismatches are `404`.
- Policy-masked ownership failures that intentionally hide existence are also `404`, named as policy-masked cases.
- Binding checks go from outer ancestor to direct parent to leaf.
- Soft-deleted models are tested beside the boundary they belong to.
- Lifecycle state checks come after binding and before validation or success.
- Validation datasets come after access/binding and before success.
- Invokable controllers stay in their own focused files.

## Related References

- [`../pattern-catalog.md`](../pattern-catalog.md)
