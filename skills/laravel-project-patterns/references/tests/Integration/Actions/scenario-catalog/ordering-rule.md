# Ordering Rule

## When To Use

Read this focused reference when the task involves ordering rule.

## Pattern

### Ordering Rule

Within an action suite, keep:

1. owner/lifecycle guards;
2. cross-record or dependency guards;
3. conditional concurrency assertions only when an existing shared-root lock
   protocol is contractual;
4. primary success;
5. required-only, partial, nullable, adjacency, reuse, isolation, or other
   success variants.

Framework contract actions keep validation datasets first, then domain
validation, then success. Generators keep primary generation, collision/reuse
branches, then retry exhaustion. Resolvers keep null branches before success.

## Related References

- [`../scenario-catalog.md`](../scenario-catalog.md)
