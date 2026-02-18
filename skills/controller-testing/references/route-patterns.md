# Route Patterns for Controller Tests

## Table of Contents

- [Action Set](#action-set)
- [Flat Resource (No Parent)](#flat-resource-no-parent)
- [One-Level Nested Resource](#one-level-nested-resource)
- [Two-Level Nested Resource](#two-level-nested-resource)
- [Three-Level Nested Resource](#three-level-nested-resource)
- [N-Level Nested Resource (General Rule)](#n-level-nested-resource-general-rule)

## Action Set

Controller tests in this style typically cover these actions when present:

- `create`
- `destroy`
- `edit`
- `index`
- `show`
- `store`
- `update`

## Route Parameter Keys

Always derive route parameter keys from your real route definitions (`php artisan route:list`).
Do not assume canonical keys from these examples when your app uses custom names or mapped keys.

## Flat Resource (No Parent)

Example route family: `organizations.*`

| Action | Route Name | Parameters |
|---|---|---|
| index | `organizations.index` | none |
| create | `organizations.create` | none |
| store | `organizations.store` | none |
| show | `organizations.show` | `organization` |
| edit | `organizations.edit` | `organization` |
| update | `organizations.update` | `organization` |
| destroy | `organizations.destroy` | `organization` |

```php
$response = get(route('organizations.index'));

$response = get(route('organizations.show', [
    'organization' => $organization,
]));
```

## One-Level Nested Resource

Example route family: `organizations.projects.*`

| Action | Route Name | Parameters |
|---|---|---|
| index | `organizations.projects.index` | `organization` |
| create | `organizations.projects.create` | `organization` |
| store | `organizations.projects.store` | `organization` |
| show | `organizations.projects.show` | `organization`, `project` |
| edit | `organizations.projects.edit` | `organization`, `project` |
| update | `organizations.projects.update` | `organization`, `project` |
| destroy | `organizations.projects.destroy` | `organization`, `project` |

```php
$response = get(route('organizations.projects.index', [
    'organization' => $organization,
]));

$response = patch(route('organizations.projects.update', [
    'organization' => $project->organization,
    'project' => $project,
]), [
    'name' => 'Renamed project',
]);
```

## Two-Level Nested Resource

Example route family: `organizations.projects.tasks.*`

| Action | Route Name | Parameters |
|---|---|---|
| index | `organizations.projects.tasks.index` | `organization`, `project` |
| create | `organizations.projects.tasks.create` | `organization`, `project` |
| store | `organizations.projects.tasks.store` | `organization`, `project` |
| show | `organizations.projects.tasks.show` | `organization`, `project`, `task` |
| edit | `organizations.projects.tasks.edit` | `organization`, `project`, `task` |
| update | `organizations.projects.tasks.update` | `organization`, `project`, `task` |
| destroy | `organizations.projects.tasks.destroy` | `organization`, `project`, `task` |

```php
$response = get(route('organizations.projects.tasks.index', [
    'organization' => $project->organization,
    'project' => $project,
]));

$response = delete(route('organizations.projects.tasks.destroy', [
    'organization' => $task->project->organization,
    'project' => $task->project,
    'task' => $task,
]));
```

## Three-Level Nested Resource

Example route family: `organizations.projects.tasks.comments.*`

| Action | Route Name | Parameters |
|---|---|---|
| index | `organizations.projects.tasks.comments.index` | `organization`, `project`, `task` |
| create | `organizations.projects.tasks.comments.create` | `organization`, `project`, `task` |
| store | `organizations.projects.tasks.comments.store` | `organization`, `project`, `task` |
| show | `organizations.projects.tasks.comments.show` | `organization`, `project`, `task`, `comment` |
| edit | `organizations.projects.tasks.comments.edit` | `organization`, `project`, `task`, `comment` |
| update | `organizations.projects.tasks.comments.update` | `organization`, `project`, `task`, `comment` |
| destroy | `organizations.projects.tasks.comments.destroy` | `organization`, `project`, `task`, `comment` |

```php
$response = get(route('organizations.projects.tasks.comments.show', [
    'organization' => $comment->task->project->organization,
    'project' => $comment->task->project,
    'task' => $comment->task,
    'comment' => $comment,
]));
```

## N-Level Nested Resource (General Rule)

If routes are nested deeper than two levels, keep the same pattern:

1. Route helper includes every ancestor parameter in order.
2. Every action test must pass all ancestor params.
3. Add binding mismatch tests so each child must belong to:
   - its direct parent, and
   - all ancestors in the chain.

Example family: `organizations.warehouses.shipments.items.*`

```php
$response = get(route('organizations.warehouses.shipments.items.show', [
    'organization' => $item->shipment->warehouse->organization,
    'warehouse' => $item->shipment->warehouse,
    'shipment' => $item->shipment,
    'item' => $item,
]));
```

Mismatch coverage should include at minimum:

- top-level parent mismatch
- each intermediate parent mismatch
- direct parent mismatch for the leaf resource
