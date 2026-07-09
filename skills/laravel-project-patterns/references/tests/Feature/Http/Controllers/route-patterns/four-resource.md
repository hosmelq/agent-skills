# Four-Resource Route Chain

## When To Use

Use this leaf for a Workspace, parent, child, and leaf route chain.

## Pattern

### Four-Resource Route Chain

| Action  | Route name                                          | Parameters                                                  |
| ------- | --------------------------------------------------- | ----------------------------------------------------------- |
| index   | `workspaces.parent-records.children.leaves.index`   | `workspace`, `parent_record`, `child_record`                |
| create  | `workspaces.parent-records.children.leaves.create`  | `workspace`, `parent_record`, `child_record`                |
| store   | `workspaces.parent-records.children.leaves.store`   | `workspace`, `parent_record`, `child_record`                |
| show    | `workspaces.parent-records.children.leaves.show`    | `workspace`, `parent_record`, `child_record`, `leaf_record` |
| edit    | `workspaces.parent-records.children.leaves.edit`    | `workspace`, `parent_record`, `child_record`, `leaf_record` |
| update  | `workspaces.parent-records.children.leaves.update`  | `workspace`, `parent_record`, `child_record`, `leaf_record` |
| destroy | `workspaces.parent-records.children.leaves.destroy` | `workspace`, `parent_record`, `child_record`, `leaf_record` |

```php
$response = get(route('workspaces.parent-records.children.leaves.show', [
    'workspace' => $leafRecord->childRecord->parentRecord->workspace,
    'parent_record' => $leafRecord->childRecord->parentRecord,
    'child_record' => $leafRecord->childRecord,
    'leaf_record' => $leafRecord,
]));
```

## Related References

- [Parent router](../route-patterns.md)
