# Derived And Conditional Resource Fields

## When To Use

Use for derived values, conditional nested resources, and nullable output.

## Pattern

- Serialize dates, decimals, enums, phone numbers, nested resources, and
  conditional fields exactly like sibling resources.
- Use `when(...)` for conditional fields. When Laravel returns a missing value,
  tests assert the key is missing. With an explicit `null` default, assert the
  key is present with `null`.
- Preserve explicit `null` for nullable contract fields such as coordinates,
  phone numbers, subdivision names, deactivation timestamps, and optional
  maximum ranges.

Deterministic derived field shape:

```php
'avatar_url' => sprintf(
    'https://avatar.example/%s',
    Str::of($this->resource->contact_email)->trim()->lower()->hash('sha256')
),
```

Conditional nested resource shape:

```php
'current_workspace' => $this->when(
    ($currentWorkspace = $this->resource->currentWorkspace) instanceof Workspace,
    fn (): WorkspaceResource => WorkspaceResource::make($currentWorkspace),
    null,
),
```

## Related References

- [Parent router](../README.md)
