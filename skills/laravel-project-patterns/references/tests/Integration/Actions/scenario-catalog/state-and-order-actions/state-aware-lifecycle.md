# State-Aware Lifecycle

## When To Use

Use when selection state constrains deactivation, reactivation, or deletion.

## Pattern

| Order | Distinct test | Required proof |
| ---: | --- | --- |
| 1 | rejects deactivating the active selection | exact exception and record stays active |
| 2 | deactivates an active non-selected record | persisted lifecycle timestamp becomes non-null |
| 3 | deactivates without replacing an existing timestamp | idempotent repeat preserves the first timestamp |
| 4 | reactivates without making the record selected | lifecycle timestamp clears and selected flag stays false |
| 5 | rejects deleting the active selection | exact exception and target remains |

## Related References

- [Parent router](../state-and-order-actions.md)
