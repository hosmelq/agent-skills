# Two-Resource Route Chain

## When To Use

Use this leaf for a Workspace plus member route chain.

## Pattern

### Two-Resource Route Chain

| Action  | Route name                          | Parameters                   |
| ------- | ----------------------------------- | ---------------------------- |
| index   | `workspaces.parent-records.index`   | `workspace`                  |
| create  | `workspaces.parent-records.create`  | `workspace`                  |
| store   | `workspaces.parent-records.store`   | `workspace`                  |
| show    | `workspaces.parent-records.show`    | `workspace`, `parent_record` |
| edit    | `workspaces.parent-records.edit`    | `workspace`, `parent_record` |
| update  | `workspaces.parent-records.update`  | `workspace`, `parent_record` |
| destroy | `workspaces.parent-records.destroy` | `workspace`, `parent_record` |

```php
$response = get(route('workspaces.parent-records.index', [
    'workspace' => $workspace,
]));

$response = patch(route('workspaces.parent-records.update', [
    'workspace' => $parentRecord->workspace,
    'parent_record' => $parentRecord,
]), [
    'name' => 'Updated',
]);
```

## Related References

- [Parent router](../route-patterns.md)
