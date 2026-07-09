# Four-Resource Store Tests

## When To Use

Read this reference for a four-resource `store` route or when auditing the matching nested binding depth.

## Pattern

### Reference Map

- [`four-resource/binding-boundaries.md`](four-resource/binding-boundaries.md): requires authentication, prevents creating from an unrelated Workspace, returns not found when parent record belongs to another Workspace, returns not found when parent record is soft deleted, returns not found when child record belongs to another parent record, returns not found when child record belongs to another Workspace, returns not found when child record is soft deleted.
- [`four-resource/request-and-range-validation.md`](four-resource/request-and-range-validation.md): does not call the action when request validation fails, rejects overlapping ranges, rejects a second open-ended range, does not evaluate action-owned range guards when request validation fails.
- [`four-resource/delegated-success.md`](four-resource/delegated-success.md): creates a leaf record through the delegated action, creates a leaf record with an open-ended maximum value.

## Related References

- [`../store.md`](../store.md)
