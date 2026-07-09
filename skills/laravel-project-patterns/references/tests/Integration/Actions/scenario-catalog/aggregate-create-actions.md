# Aggregate Create Actions

## When To Use

Use when one action creates a root plus nested children, resolves several
optional public-ID relations, or applies defaults that depend on related state.

## Pattern

Keep every applicable row; similar scalar fields do not make these scenarios
duplicates.

| Order | Distinct test | Required proof |
| ---: | --- | --- |
| 1 | creates the fully related aggregate and every explicit initial child | returned root identity, child count, all resolved internal keys, and representative child values |
| 2 | applies selected-state, timestamp, and current-relation defaults | omitted values resolve from the one eligible selected record and documented fallback relation |
| 3 | does not infer the fallback relation when the current relation is explicit | explicit current relation wins while the unrelated optional relation remains null |
| 4 | accepts explicit nulls and applies only documented defaults | every nullable root/child input remains null except named server defaults |
| 5 | rejects creation when the required selected record is missing | no repair/create side effect and no aggregate row |
| 6 | rejects an explicit unavailable selected record | cover cross-owner, inactive, soft-deleted, and wrong-category states that the resolver distinguishes |
| 7 | treats related-record links as suggestions until independently confirmed | selecting one relation does not copy its optional sibling relations |
| 8 | persists explicitly confirmed related suggestions | independently submitted compatible relations persist together |
| 9 | rejects incompatible ownership between related selections | exact domain exception and no aggregate |
| 10 | allows an explicitly confirmed relation to differ without mutating the related record | aggregate uses the submitted relation and the source configuration stays unchanged |
| 11 | rechecks every optional relation inside the transaction | each owner/state variant fails atomically; use one named dataset only when setup and exception contract are identical |
| 12 | requires a dependent option to belong to the selected parent option | exact mismatch exception |
| 13 | rejects a dependent option without its required parent option | exact missing-parent exception |
| 14 | enforces root measurement compatibility while child measurements remain independent | root rule fails independently; a compatible root may contain a differently measured child |
| 15 | allows measurements before the optional compatibility relation is confirmed | measurement persists with that relation null |
| 16 | rolls back the root and earlier children when a later child fails | zero root and child rows remain |
| 17 | rejects normalized active-key collisions and allows soft-deleted reuse | normalized conflict fails; deleted value may be recreated in the same owner |
| 18 | maps the named unique-index race to the domain exception | competing insert survives and the candidate maps only the expected constraint |
| 19 | rechecks a newly selected child relation before assigning consumers | unavailable relation fails and no child references it |
| 20 | preserves an existing relation-lock assertion only when its complete competing protocol is already documented | exact selected roots lock before consumer writes; never infer this from aggregate creation alone |

### Focused Examples

- [Nested Defaults And Rollback](aggregate-actions/nested-defaults-and-rollback.md)
- [Relation Confirmation And Continuity](aggregate-actions/relation-confirmation-and-continuity.md)
- [Named Constraint Race Mapping](aggregate-actions/named-constraint-race-mapping.md)

## Related References

- [Parent catalog](../scenario-catalog.md)
- [Existing lock protocol gate](existing-shared-root-lock-protocol-conditional.md)
