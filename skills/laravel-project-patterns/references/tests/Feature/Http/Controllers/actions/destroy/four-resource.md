# Four-Resource Destroy Tests

## When To Use

Read this reference for a four-resource `destroy` route or when auditing the matching nested binding depth.

## Pattern

### Reference Map

- [`four-resource/binding-boundaries.md`](four-resource/binding-boundaries.md): requires authentication, prevents deleting from an unrelated Workspace, returns not found when parent record belongs to another Workspace, returns not found when parent record is soft deleted, returns not found when child record belongs to another parent record, returns not found when child record belongs to another Workspace, returns not found when child record is soft deleted, returns not found when leaf record belongs to another child record, returns not found when leaf record belongs to another Workspace, returns not found when leaf record is soft deleted.
- [`four-resource/lifecycle-and-success.md`](four-resource/lifecycle-and-success.md): prevents deleting when the parent record is inactive, deletes a leaf record.

## Related References

- [`../destroy.md`](../destroy.md)
