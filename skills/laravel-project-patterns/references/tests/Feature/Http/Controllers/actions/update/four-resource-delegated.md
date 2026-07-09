# Four-Resource Delegated Update Tests

## When To Use

Read this reference for a four-resource update route or when auditing every nested binding boundary.

## Pattern

### Reference Map

- [`four-resource-delegated/binding-boundaries.md`](four-resource-delegated/binding-boundaries.md): requires authentication, prevents updating from an unrelated Workspace, returns not found when parent record belongs to another Workspace, returns not found when parent record is soft deleted, returns not found when child record belongs to another parent record, returns not found when child record belongs to another Workspace, returns not found when child record is soft deleted, returns not found when leaf record belongs to another child record, returns not found when leaf record belongs to another Workspace, returns not found when leaf record is soft deleted.
- [`four-resource-delegated/request-action-boundary.md`](four-resource-delegated/request-action-boundary.md): does not call the action when request validation fails, prevents updating when dependent records exist, passes partial input to the delegated action.

## Related References

- [`../update.md`](../update.md)
