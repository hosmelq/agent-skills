# Existing Shared-Root Lock Protocol (Conditional)

## When To Use

Read this focused reference when the task involves existing shared-root lock protocol (conditional).

## Pattern

### Existing Shared-Root Lock Protocol (Conditional)

Use these rows only while testing a lock protocol that already exists in the
live actions. Do not select one row in isolation. First enumerate the competing
create, update, lifecycle, and delete actions and confirm that each locks the
same stable root in the same order. Transactions, active-state guards,
moving/ordering, and initial/default selection do not make these rows applicable.

| Order | Distinct test | Required proof |
| ---: | --- | --- |
| 1 | rejects an inactive record from freshly locked state | exact domain exception after the shared root is re-read |
| 2 | locks the shared root before resolving active state | exact root model is selected `FOR UPDATE` |
| 3 | resolves an active locked root | returned identity is the freshly locked record |
| 4 | locks the shared root before dependent creation | create locks the same root as every competing lifecycle/delete action |
| 5 | locks the shared root before guarded deactivation | deactivation locks before querying dependents |
| 6 | locks the shared root before guarded deletion | deletion locks before querying active or soft-deleted dependents |
| 7 | locks the shared root before a protected-field update | update locks before checking dependent rows that restrict that field |

## Related References

- [`../scenario-catalog.md`](../scenario-catalog.md)
