# Three-Resource Route Chain

## When To Use

Use this leaf for a Workspace, parent, and child route chain.

## Pattern

### Three-Resource Route Chain

| Action  | Route name                                   | Parameters                                   |
| ------- | -------------------------------------------- | -------------------------------------------- |
| index   | `workspaces.parent-records.children.index`   | `workspace`, `parent_record`                 |
| create  | `workspaces.parent-records.children.create`  | `workspace`, `parent_record`                 |
| store   | `workspaces.parent-records.children.store`   | `workspace`, `parent_record`                 |
| show    | `workspaces.parent-records.children.show`    | `workspace`, `parent_record`, `child_record` |
| edit    | `workspaces.parent-records.children.edit`    | `workspace`, `parent_record`, `child_record` |
| update  | `workspaces.parent-records.children.update`  | `workspace`, `parent_record`, `child_record` |
| destroy | `workspaces.parent-records.children.destroy` | `workspace`, `parent_record`, `child_record` |

```php
$response = get(route('workspaces.parent-records.children.index', [
    'workspace' => $parentRecord->workspace,
    'parent_record' => $parentRecord,
]));

$response = delete(route('workspaces.parent-records.children.destroy', [
    'workspace' => $childRecord->parentRecord->workspace,
    'parent_record' => $childRecord->parentRecord,
    'child_record' => $childRecord,
]));
```

## Related References

- [Parent router](../route-patterns.md)
