# Settings Or Singleton Routes

## When To Use

Use this leaf for a Workspace-bound settings or singleton route.

## Pattern

### Settings or Singleton Route

```php
$response = get(route('workspaces.settings.general', [
    'workspace' => $workspace,
]));

$response = patch(route('workspaces.update', [
    'workspace' => $workspace,
]), [
    'name' => 'Example Workspace',
]);
```

## Related References

- [Parent router](../route-patterns.md)
