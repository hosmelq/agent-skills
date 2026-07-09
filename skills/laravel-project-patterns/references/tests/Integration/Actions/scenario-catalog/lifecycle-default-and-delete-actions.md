# Lifecycle, Default, and Delete Actions

## When To Use

Read this focused reference when the task involves lifecycle, default, and delete actions.

## Pattern

### Lifecycle, Default, and Delete Actions

#### Direct and Guarded Lifecycle

| Order | Distinct test | Required proof |
| ---: | --- | --- |
| 1 | deactivates an active record | timestamp persists; repeated transition remains safe when the action is idempotent |
| 2 | reactivates an inactive record | timestamp clears; repeated transition remains safe when the action is idempotent |
| 3 | rejects reactivating a nested record whose related owner is inactive | exact exception and nested record stays inactive |
| 4 | rejects deactivation while active dependents exist | exact exception and root remains active |
| 5 | deactivates a root with no dependents | timestamp persists |
| 6 | deactivates when dependents are inactive | inactive dependents do not block |
| 7 | deactivates when dependents are soft deleted | soft-deleted dependents do not block unless the action explicitly uses `withTrashed()` |

#### Exclusive Default

| Order | Distinct test | Required proof |
| ---: | --- | --- |
| 1 | selects a record and clears the current active default | selected row becomes default and active sibling clears atomically |
| 2 | does not clear a soft-deleted historical default | default scope/predicate ignores deleted rows |
| 3 | clears defaults only inside the same direct owner | another owner's default remains unchanged |

#### Delete

| Order | Distinct test | Required proof |
| ---: | --- | --- |
| 1 | deletes a directly targeted record | correct soft/hard deletion contract |
| 2 | deletes the current default without selecting a replacement | owner is intentionally left without a default |
| 3 | deletes a root and its operational records | every owned operational row is removed in one operation |
| 4 | preserves operational rows belonging to another root | cascade is owner-isolated |
| 5 | rejects deletion while active configuration children exist | configuration-specific exception and root remains |
| 6 | rejects deletion while soft-deleted configuration children exist | `withTrashed()` keeps historical configuration blocking |
| 7 | rejects deletion while active operational assignments exist | operational-dependency exception and root remains |
| 8 | rejects deletion while soft-deleted operational assignments exist | `withTrashed()` keeps historical assignment blocking |
| 9 | deletes a root without dependencies | root is deleted after guard passes |
| 10 | rejects nested deletion when the derived owner is inactive | target remains undeleted |
| 11 | deletes the nested target for an active owner | target deletion persists |

## Related References

- [`../scenario-catalog.md`](../scenario-catalog.md)
