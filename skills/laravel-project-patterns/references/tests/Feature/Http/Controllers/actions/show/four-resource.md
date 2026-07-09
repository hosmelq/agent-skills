# Four-Resource Show Tests

## When To Use

Read this reference for a four-resource `show` route or when auditing the matching nested binding depth.

## Pattern

### Reference Map

- [`four-resource/binding-boundaries.md`](four-resource/binding-boundaries.md): requires authentication, prevents viewing from an unrelated Workspace, returns not found when parent record belongs to another Workspace, returns not found when parent record is soft deleted, returns not found when child record belongs to another parent record, returns not found when child record belongs to another Workspace, returns not found when child record is soft deleted, returns not found when leaf record belongs to another child record, returns not found when leaf record belongs to another Workspace, returns not found when leaf record is soft deleted.
- [`four-resource/page-contract.md`](four-resource/page-contract.md): shows a leaf record, shows a leaf record when the parent record is inactive if read continuity is allowed.

## Related References

- [`../show.md`](../show.md)
