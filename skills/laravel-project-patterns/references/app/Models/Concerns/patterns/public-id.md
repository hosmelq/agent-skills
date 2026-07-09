# Public ID Concern

## When To Use

Use this leaf for public ID generation, finder, and route-key behavior.

## Pattern

### Public ID Concern

Use a public ID concern for public NanoID generation, finder, and route-key behavior.

- Use `HasUniqueStringIds` and generate IDs with the NanoID generator and the configured alphabet enum's alphanumeric case.
- Keep `PUBLIC_ID_LENGTH` at `10` unless migrations, tests, and public ID format checks change together.
- By default, `getRouteKeyName()` returns `public_id` and `uniqueIds()` returns `['public_id']`.
- Models may still use the trait for NanoID generation/finders while overriding route binding to another key such as `slug`.
- `wherePublicId(string $publicId)` is the reusable local scope for public ID constraints. Finder methods should delegate to this scope so composed queries and direct finder calls share the same behavior.
- `findByPublicId(string $publicId): null|static` and `findOrFailByPublicId(string $publicId): static` query the `public_id` column through the local scope. Use them at HTTP/form boundaries after validation.
- `isValidUniqueId()` accepts case-insensitive alphanumeric IDs matching the configured length. The database uses case-insensitive text plus format checks, so route binding and finder tests must cover case-insensitive behavior.

```php
public function newUniqueId(): string
{
    return resolve(NanoidGenerator::class)->formattedId(ExampleAlphabet::Alphanumeric(), self::PUBLIC_ID_LENGTH);
}

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

- [Parent router](../README.md)
