# Ensure Exclusive State

## When To Use

Use when an action selects, promotes, or creates one eligible record.

## Pattern

| Order | Distinct test | Required proof |
| ---: | --- | --- |
| 1 | creates the selected record when no eligible candidate exists | server defaults, owner scope, selected flag, and returned identity |
| 2 | returns the current active eligible selection | no duplicate is created and the same model is returned |
| 3 | promotes the first active eligible candidate instead of creating | domain order decides the candidate and the selector collaborator receives it |
| 4 | ignores an inactive historical selection | inactive selection is not returned or promoted |
| 5 | ignores a soft-deleted historical selection | deleted selection is not returned or promoted |

## Related References

- [Parent router](../state-and-order-actions.md)
