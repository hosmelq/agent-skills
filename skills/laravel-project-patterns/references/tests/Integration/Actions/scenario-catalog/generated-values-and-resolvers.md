# Generated Values and Resolvers

## When To Use

Read this focused reference when the task involves generated values and resolvers.

## Pattern

### Generated Values and Resolvers

#### Owner-Scoped Normalized Generator

| Order | Distinct test | Required proof |
| ---: | --- | --- |
| 1 | generates from owner format/alphabet settings | collaborator arguments and final formatting |
| 2 | retries after a normalized collision | raw formatting differences still collide after normalization |
| 3 | does not reuse a value held by an inactive record | inactive records reserve values when the invariant counts them |
| 4 | reuses a value from a soft-deleted record | default soft-delete scope excludes deleted rows |
| 5 | reuses a matching normalized value from another owner | uniqueness is owner-scoped |
| 6 | throws after the owner-scoped maximum attempts | exact attempt count and exception message |

#### Global Single-Use-Code Generator

| Order | Distinct test | Required proof |
| ---: | --- | --- |
| 1 | creates a fresh code for an identifier | identifier, generated value, and exact expiry persist |
| 2 | deletes existing unused codes for the same identifier | old active code disappears before new code persists |
| 3 | retries after a globally active collision | next unique candidate persists |
| 4 | avoids values previously used by another identifier | used values remain globally reserved |
| 5 | throws after the global-code maximum attempts | exact attempt count and no candidate persists |

#### Nullable Active-Only Resolver

| Order | Distinct test | Required proof |
| ---: | --- | --- |
| 1 | returns `null` when no normalized value matches | nullable finder contract |
| 2 | returns `null` for a soft-deleted match | deleted rows are excluded |
| 3 | returns `null` for an inactive match | active-state filter |
| 4 | returns `null` for a match in another owner | owner isolation |
| 5 | resolves an active match from every supported raw format | named dataset proves normalization and returned model identity |

## Related References

- [`../scenario-catalog.md`](../scenario-catalog.md)
