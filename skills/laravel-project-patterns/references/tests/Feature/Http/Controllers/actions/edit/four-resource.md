# Four-Resource Edit Tests

## When To Use

Read this reference for a four-resource `edit` route or when auditing the matching nested binding depth.

## Pattern

### Reference Map

- [`four-resource/binding-boundaries.md`](four-resource/binding-boundaries.md): requires authentication, prevents viewing from an unrelated Workspace, returns not found when parent record belongs to another Workspace, returns not found when parent record is soft deleted, returns not found when child record belongs to another parent record, returns not found when child record belongs to another Workspace, returns not found when child record is soft deleted, returns not found when leaf record belongs to another child record, returns not found when leaf record belongs to another Workspace, returns not found when leaf record is soft deleted.
- [`four-resource/lifecycle-and-page.md`](four-resource/lifecycle-and-page.md): prevents viewing when the parent record is inactive, shows the edit leaf record page.

## Related References

- [`../edit.md`](../edit.md)
