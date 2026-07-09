# Four-Resource Index Tests

## When To Use

Read this reference for a four-resource `index` route or when auditing the matching nested binding depth.

## Pattern

### Reference Map

- [`four-resource/binding-boundaries.md`](four-resource/binding-boundaries.md): requires authentication, prevents listing from an unrelated Workspace, returns not found when parent record belongs to another Workspace, returns not found when parent record is soft deleted, returns not found when child record belongs to another parent record, returns not found when child record belongs to another Workspace, returns not found when child record is soft deleted.
- [`four-resource/listing-contract.md`](four-resource/listing-contract.md): lists leaf records from the child record, lists leaf records when the parent record is inactive if read continuity is allowed, and excludes records from another child, another `Workspace`, or soft-deleted state.

## Related References

- [`../index.md`](../index.md)
