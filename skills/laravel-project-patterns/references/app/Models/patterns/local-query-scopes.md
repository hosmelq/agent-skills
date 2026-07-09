# Local Query Scopes

## When To Use

Read this focused reference when the task involves local query scopes.

## Pattern

### Local Query Scopes

- Define new local Eloquent scopes with Laravel's `#[Scope]` attribute on protected methods. Do not use legacy `scopeFoo(...)` methods for new code unless an existing file already establishes that local pattern.
- Import `Illuminate\Database\Eloquent\Attributes\Scope` and `Illuminate\Database\Eloquent\Builder` for scoped methods.
- Name the first parameter `$builder`. For dynamic scopes, place additional parameters after the builder.
- Return `void` when mutating the builder in place.
- Use `$builder->qualifyColumn(...)` when filtering a column owned by the scoped model, especially inside reusable concerns or scopes that may be composed with joins or relationship queries.
- Do not add a standalone test for a simple scope wrapper when a public method, finder, controller path, or system behavior already proves the same query constraint.

```php
/**
 * @param Builder<static> $builder
 */
#[Scope]
protected function wherePublicId(Builder $builder, string $publicId): void
{
    $builder->where($builder->qualifyColumn('public_id'), $publicId);
}
```

## Related References

- [`../README.md`](../README.md)
