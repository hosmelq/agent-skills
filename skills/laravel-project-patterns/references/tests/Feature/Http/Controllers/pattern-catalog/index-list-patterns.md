# Index / List Patterns

## When To Use

Read this focused reference when the task involves index / list patterns.

## Pattern

### Index / List Patterns

Index tests prove collection scoping:

- include an in-scope row;
- exclude another Workspace;
- exclude another direct parent;
- exclude same-`Workspace` wrong ancestor graphs;
- exclude redundant ownership mismatches;
- exclude soft-deleted rows unless included by contract;
- preserve serialized public-ID resource shape.

## Related References

- [`../pattern-catalog.md`](../pattern-catalog.md)
