# Aggregate Update Actions

## When To Use

Use when an update combines partial scalar changes, nullable public-ID
relations, historical-current relation continuity, or final effective-value
validation.

## Pattern

| Order | Distinct test | Required proof |
| ---: | --- | --- |
| 1 | updates every editable field and relation without mutating related configuration | returned identity, exact persistence, and unchanged related configuration |
| 2 | performs a partial update and preserves omitted values | every representative omitted scalar/relation remains unchanged |
| 3 | preserves a submitted current historical relation while rejecting it for a new assignment | current soft-deleted/inactive relation may remain; another aggregate cannot newly select it |
| 4 | clears nullable relations and scalar fields explicitly | submitted nulls persist as null and differ from omission |
| 5 | rejects ordinary updates for every terminal owner state | exact exception and original fields |
| 6 | rejects each incompatible relationship combination | owner mismatch, missing required parent relation, and parent/dependent mismatch remain distinct dataset rows |
| 7 | validates final composite measurement groups from persisted plus submitted values | effective weight/unit and dimension groups are complete after merging partial input |
| 8 | enforces final compatibility with the selected related configuration | exact domain exception |
| 9 | enforces action-owned required values even outside HTTP validation | direct action call cannot clear the invariant field |
| 10 | enforces normalized active-key uniqueness while allowing self and deleted reuse | unchanged normalized self succeeds, active conflict fails, deleted conflict may be reused |
| 11 | maps the named unique-index race to the domain exception | only the named constraint is translated; cleanup runs in `finally` |

### Focused Examples

- [Relation Confirmation And Continuity](aggregate-actions/relation-confirmation-and-continuity.md)
- [Named Constraint Race Mapping](aggregate-actions/named-constraint-race-mapping.md)

## Related References

- [Parent catalog](../scenario-catalog.md)
- [Ordinary update catalog](update-actions.md)
