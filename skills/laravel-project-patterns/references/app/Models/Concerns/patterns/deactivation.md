# Deactivation Concern

## When To Use

Use this leaf for the reusable deactivation concern contract.

## Pattern

### Deactivation Concern

Use a deactivation concern only for models with a nullable `deactivated_at` timestamp cast to `datetime`.

- Keep the trait limited to generic model behavior: `isActive()`, `isDeactivated()`, idempotent `deactivate()` / `reactivate()` mutations, and `active()` / `deactivated()` local scopes.
- Keep `deactivated_at` documented as `@property-read null|CarbonImmutable $deactivated_at` on the trait and on consuming models.
- Do not add a global scope for deactivation. Deactivated records remain historically visible unless the caller explicitly applies the local scope.
- Do not hide workflow rules inside the trait. Controllers, policies, requests, actions, and transactional mutations still own authorization, validation, stale-state checks, locking, redirects, and interface messages.
- Use `$model->update([...])` for persisted state changes because models are globally unguarded.

```php
public function deactivate(): void
{
    if ($this->isActive()) {
        $this->update(['deactivated_at' => now()]);
    }
}

/**
 * @param Builder<static> $builder
 */
#[Scope]
protected function active(Builder $builder): void
{
    $builder->whereNull($builder->qualifyColumn('deactivated_at'));
}
```

## Related References

- [Parent router](../README.md)
