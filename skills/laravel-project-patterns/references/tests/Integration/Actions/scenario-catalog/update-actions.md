# Update Actions

## When To Use

Read this focused reference when the task involves update actions.

## Pattern

### Update Actions

#### Ordinary Partial Update

| Order | Distinct test | Required proof |
| ---: | --- | --- |
| 1 | updates every supported field | returned identity and exact persistence |
| 2 | updates only provided ordinary fields | omitted `Optional` values stay unchanged |
| 3 | clears nullable ordinary fields | explicit `null` differs from omission |

#### Guarded Field Update

| Order | Distinct test | Required proof |
| ---: | --- | --- |
| 1 | rejects update when the owner is inactive | exact exception and original values |
| 2 | rejects changing the protected field while dependents exist | protected value remains unchanged |
| 3 | updates every field for an active allowed record | returned identity and exact persistence |
| 4 | updates unrelated fields while dependents exist and protected field is omitted | guard is field-sensitive, not a blanket rejection |
| 5 | updates only provided guarded-record fields | other attributes stay unchanged |
| 6 | clears nullable guarded-record fields | explicit `null` persists |

#### Half-Open Range Update

| Order | Distinct test | Required proof |
| ---: | --- | --- |
| 1 | rejects update when the owning root is inactive | exact exception and original range |
| 2 | rejects an overlapping effective range | submitted plus persisted endpoints are evaluated |
| 3 | rejects a second open-ended effective range | only one terminal range remains |
| 4 | updates the range for an active owner | returned identity and exact decimal persistence |
| 5 | updates a range between adjacent neighbors | `[)` endpoints may touch on both sides |
| 6 | updates only provided range fields | omitted endpoints fall back to stored values |
| 7 | clears the nullable maximum | explicit `null` creates an open endpoint |
| 8 | updates the minimum while stored maximum is open-ended | omitted maximum stays `null` |
| 9 | ignores a soft-deleted conflicting range during update | replacement range persists |
| 10 | updates to match a range in another direct scope | direct-parent isolation |
| 11 | reuses an open-ended range after soft delete | deleted terminal interval does not block |

## Related References

- [`../scenario-catalog.md`](../scenario-catalog.md)
