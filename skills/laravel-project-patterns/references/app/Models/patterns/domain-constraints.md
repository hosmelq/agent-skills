# Domain Constraints

## When To Use

Read this focused reference when the task involves domain constraints.

## Pattern

### Domain Constraints

- `deactivated_at` is a domain state, not a soft delete. Cast it to `datetime`, include it in PHPDoc as `null|CarbonImmutable`, use `HasDeactivation` for reusable active/deactivated scopes and idempotent state transitions, and keep `deactivated()` factory states when siblings have them.
- `HasDeactivation` provides local `active()` / `deactivated()` scopes and `deactivate()` / `reactivate()` helpers. Do not replace controller, policy, request, or action-level lifecycle checks with the trait when a workflow needs explicit authorization, validation, stale-state protection, locking, redirects, or interface messages.
- Soft-delete-aware uniqueness uses partial indexes with `WHERE deleted_at IS NULL`. Deactivated records still reserve values unless the index excludes `deactivated_at`.
- Coordinate columns use decimal storage, `float` model casts, nullable PHPDoc, validation ranges, and database checks: latitude `-90..90`, longitude `-180..180`. Keep paired coordinate validation in requests when both values are required together.
- Decimal columns use string PHPDoc with `decimal:n` casts. Use the precision from sibling migrations and factories.
- Prunable temporary-code models should return a typed `Builder<static>` and keep expiration/used cleanup rules in `prunable()`.

When used and expired records have different retention windows, express both
branches in the prunable query:

```php
/**
 * @return Builder<static>
 */
public function prunable(): Builder
{
    $expirationCutoff = now();
    $usedCutoff = now()->subMonth();

    return static::query()
        ->where(function (Builder $builder) use ($expirationCutoff): void {
            $builder
                ->where('expires_at', '<=', $expirationCutoff)
                ->whereNull('used_at');
        })
        ->orWhere(function (Builder $builder) use ($usedCutoff): void {
            $builder
                ->whereNotNull('used_at')
                ->where('used_at', '<=', $usedCutoff);
        });
}
```

Keep the selection query side-effect free. Unit tests prove which records are
selected; pruning execution remains framework behavior.

## Related References

- [`../README.md`](../README.md)
