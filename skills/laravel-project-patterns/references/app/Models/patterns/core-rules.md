# Core Rules

## When To Use

Read this focused reference when the task involves core rules.

## Pattern

### Core Rules

- Use strict types and explicit imports.
- Do not add `$fillable` or `$guarded` when the app globally calls `Model::unguard()`.
- Use `$model->update([...])` for normal persisted attribute mutations in app-owned code. Do not use `forceFill(...)->save()` as a mass-assignment workaround.
- Add `HasFactory` with a generic PHPDoc `@use HasFactory<ModelFactory>`.
- Add `HasDeactivation` for models with a nullable `deactivated_at` lifecycle timestamp. Keep it as explicit domain state, not as `SoftDeletes` or a global scope.
- Add `HasPublicId` for routeable or externally exposed domain models with a `public_id` column.
- Add `SoftDeletes` only when the migration includes `softDeletes()`.
- Let Pint order class trait uses, but keep the `@use HasFactory<ModelFactory>` PHPDoc directly attached to the `HasFactory` use statement.
- Add `#[Override]` on overridden framework hooks/properties/methods where sibling code does.
- Use `protected function casts(): array` instead of a `$casts` property when sibling models use the method.
- Keep model defaults in `protected $attributes = [...]` when defaults are part of the domain.
- Use enum casts for enum-backed strings; use `decimal:n`, `integer`, `boolean`, `array`, `float`, `hashed`, value-object casts, and `datetime` casts for scalar normalization.
- Use `CarbonImmutable` in PHPDoc for timestamps when project date behavior expects immutable timestamps in tests.

Default attribute pattern:

```php
#[Override]
protected $attributes = [
    'enabled' => false,
    'mode' => ExampleMode::Default,
];
```

## Related References

- [`../README.md`](../README.md)
