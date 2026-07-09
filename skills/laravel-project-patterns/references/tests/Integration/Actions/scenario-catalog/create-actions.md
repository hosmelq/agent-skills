# Create Actions

## When To Use

Read this focused reference when the task involves create actions.

## Pattern

### Create Actions

#### Ordinary and Coordinated Create

| Order | Distinct test | Required proof |
| ---: | --- | --- |
| 1 | creates a record with every supported field | returned model identity plus exact persisted mapped values |
| 2 | creates a record with only required fields | model defaults apply and omitted `Optional` fields are absent |
| 3 | creates a root for an actor and switches current context | root persists, owner membership/pivot persists, and current context changes in one transaction |
| 4 | creates that coordinated root with only required fields | defaults plus membership/current-context side effects |

#### Guarded Child Create

| Order | Distinct test | Required proof |
| ---: | --- | --- |
| 1 | rejects creation when the owner is inactive | exact domain exception and no child row |
| 2 | creates under an active owner | direct relationship key and submitted fields persist |
| 3 | creates under an active owner with only required fields | omitted values use defaults |

#### Composed Create

| Order | Distinct test | Required proof |
| ---: | --- | --- |
| 1 | rejects a related public ID from another `Workspace` | owner-scoped lookup fails; guard/generator collaborators after lookup are not called; nothing persists |
| 2 | rejects an inactive related record | reusable guard throws; generator is not called; nothing persists |
| 3 | creates the composed record | guard receives the derived owner and related record in order; generator receives the direct parent; submitted public ID becomes the related internal key; denormalized `Workspace` persists |
| 4 | creates the composed record with only required fields | the same collaborator/ownership contract holds while defaults apply |

#### Half-Open Range Create

| Order | Distinct test | Required proof |
| ---: | --- | --- |
| 1 | rejects creation when the owning root is inactive | exact domain exception and no range |
| 2 | rejects an overlapping range | original range remains and candidate is absent |
| 3 | rejects a second open-ended range | only the existing terminal range remains |
| 4 | creates a bounded range | returned identity and exact decimal persistence |
| 5 | creates an open-ended range when maximum is omitted | `Optional` omission persists the open endpoint |
| 6 | creates a range adjacent to both neighbors | half-open endpoints may touch |
| 7 | ignores a soft-deleted conflicting range during create | replacement persists and deleted row remains deleted |
| 8 | creates the same range in another direct scope | owner/direct-parent isolation |
| 9 | recreates an open-ended range after soft delete | nullable endpoint can be reused |

## Related References

- [`../scenario-catalog.md`](../scenario-catalog.md)
