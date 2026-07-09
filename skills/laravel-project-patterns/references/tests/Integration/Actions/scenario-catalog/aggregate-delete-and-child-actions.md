# Aggregate Delete And Child Actions

## When To Use

Use when nested child mutations depend on aggregate lifecycle state, an
optional historical relation, a minimum live-child invariant, or aggregate
deletion ownership.

## Pattern

### Child Create And Update

| Order | Distinct test | Required proof |
| ---: | --- | --- |
| 1 | creates or fully updates a child with every supported field | returned identity and exact persistence |
| 2 | creates with required values or updates only submitted values | defaults/omission preserve unrelated fields |
| 3 | preserves an unchanged historical relation on update | omitted and explicitly unchanged public-ID paths both retain it |
| 4 | clears the nullable child relation | explicit null persists |
| 5 | rejects a newly assigned unavailable relation | cross-owner, inactive, and soft-deleted states fail |
| 6 | rejects create/update for every terminal aggregate state | exact operation-specific exception and no mutation |
| 7 | preserves an existing selected-relation lock assertion only when its complete competing protocol is documented | newly selected relation locks before assignment; omission, null, and unchanged relation do not invent a lock |

### Child Delete

| Order | Distinct test | Required proof |
| ---: | --- | --- |
| 1 | soft deletes only the target child | another live sibling remains |
| 2 | locks the aggregate before counting live children when the live protocol requires it | query order proves lock before count |
| 3 | rejects deleting the last live child | both no-sibling and soft-deleted-sibling variants keep the target live |
| 4 | rejects deletion for every terminal aggregate state | target remains live |

### Aggregate Delete

| Order | Distinct test | Required proof |
| ---: | --- | --- |
| 1 | atomically deletes the aggregate and only its live children | active children delete; historical child timestamp remains unchanged |
| 2 | rejects deletion for every terminal state | aggregate and its child remain live |
| 3 | rolls back child deletion when root deletion fails | root and earlier children remain live after the exact unexpected exception |

## Related References

- [Parent catalog](../scenario-catalog.md)
- [Model-targeted mutation examples](../patterns/model-targeted-mutations.md)
- [Existing lock protocol gate](existing-shared-root-lock-protocol-conditional.md)
