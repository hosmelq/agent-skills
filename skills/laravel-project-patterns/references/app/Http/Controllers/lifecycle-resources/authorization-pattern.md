# Authorization Pattern

## When To Use

Read this focused reference when the task involves authorization pattern.

## Pattern

### Authorization Pattern

Controllers stay resourceful; policy ability names may stay verb-oriented because permissions are actions.

Examples:

```php
public static function middleware(): array
{
    return [
        new Middleware('can:update,parent_record'),
    ];
}
```

Use existing update/delete abilities for lifecycle transitions that are ordinary update/delete permissions for the model. Use transition-specific abilities only when the workflow has separate authorization or denial messages.

```php
public static function middleware(): array
{
    return [
        new Middleware('can:deactivate,parentRecord', only: ['store']),
        new Middleware('can:reactivate,parentRecord', only: ['destroy']),
    ];
}
```

Prefer the ability names already used by sibling policies. If no local precedent exists, choose names that read clearly in tests and denial messages (`activate`, `deactivate`, `reactivate`, `approve`, `reject`).

## Related References

- [`../lifecycle-resources.md`](../lifecycle-resources.md)
