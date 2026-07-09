# Explicit Selection

## When To Use

Use when an action explicitly selects one eligible record.

## Pattern

| Order | Distinct test | Required proof |
| ---: | --- | --- |
| 1 | rejects selecting an inactive record | exact exception and flags remain unchanged |
| 2 | rejects selecting a record from an ineligible category | exact exception and flags remain unchanged |
| 3 | selects an active eligible record as the only current selection | active sibling clears and target becomes selected |
| 4 | clears inactive and soft-deleted historical selection flags | `withTrashed()` cleanup is owner-scoped and stale flags clear |

## Related References

- [Parent router](../state-and-order-actions.md)
