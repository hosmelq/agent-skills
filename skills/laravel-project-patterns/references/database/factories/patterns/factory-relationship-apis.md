# Factory Relationship APIs

## When To Use

Read this focused reference when the task involves factory relationship apis.

## Pattern

### Factory Relationship APIs

- Use `for($model)` for belongs-to relationships.
- Use `has(RelatedFactory::factory(), 'relationshipName')` when the relationship name is not the Laravel default.
- Use `recycle($model)` only when it does not hide the ownership graph being proven. It is acceptable to recycle a shared ancestor in a binding, listing, or validation test when the purpose is to isolate a lower boundary and the expected graph is still explicit in the assertion.
- Use `sequence(...)` to create related rows that differ only in small attributes.
- Use `afterCreating` when relationship creation must inspect the persisted parent or attach/sync pivot values.

Use `recycle($workspace)` when testing a non-ownership property and the current
test explicitly establishes that every participating factory belongs to that
`Workspace`. Avoid `recycle($model)` when it would conceal the relationship
under test; create related records explicitly when the assertion must prove the
graph.

## Related References

- [`../README.md`](../README.md)
