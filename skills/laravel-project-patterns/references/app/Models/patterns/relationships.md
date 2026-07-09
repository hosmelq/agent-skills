# Relationships

## When To Use

Read this focused reference when the task involves relationships.

## Pattern

### Relationships

- Always type relationship methods with Laravel relation return types.
- Add PHPDoc generics above relationship methods:
  - `@return BelongsTo<ParentRecord, $this>`
  - `@return HasMany<ChildRecord, $this>`
  - `@return HasOne<ChildRecord, $this>`
  - `@return BelongsToMany<RelatedRecord, $this, Membership, 'membership'>`
- For non-conventional foreign keys, pass the key explicitly.
- For pivot models that use incrementing IDs, set `public $incrementing = true;` on the custom pivot class.
- If a relationship has scoped behavior, name it descriptively, for example `defaultChild()` for `hasOne(...)->where('is_default', true)`.
- Model ownership follows the stored IDs. Direct children belong to their direct parent or `Workspace`. Deeper children resolve ownership through their parent chain.
- When a child stores denormalized ownership, keep both relationship paths explicit and test the invalid graph only where route/list/authorization behavior needs it.
- Create nested children through the parent relationship when that relationship defines the domain boundary.

Pivot relationship pattern:

```php
/**
 * @return BelongsToMany<Workspace, $this, Membership, 'membership'>
 */
public function workspaces(): BelongsToMany
{
    return $this->belongsToMany(Workspace::class)
        ->as('membership')
        ->using(Membership::class)
        ->withTimestamps();
}
```

Filtered single-relation pattern:

```php
/**
 * @return HasOne<ChildRecord, $this>
 */
public function defaultChild(): HasOne
{
    return $this->hasOne(ChildRecord::class)
        ->where('is_default', true);
}
```

Accessor pattern:

```php
/**
 * @return Attribute<string, never>
 */
protected function displayName(): Attribute
{
    return Attribute::get(fn (): string => $this->name ?? $this->secondary_label ?? '');
}
```

Pure normalization belongs on the model only when it is part of that model's
domain identity and can run without persistence:

```php
public static function normalizeContactNumber(string $value): string
{
    return preg_replace('/[^0-9]+/', '', $value) ?? '';
}
```

Keep a pure helper deterministic for blank, formatted, and already-normalized
inputs. Do not make it query the database or depend on mutable request state.

## Related References

- [`../README.md`](../README.md)
