# Local Schema Rules

## When To Use

Read this focused reference when the task involves local schema rules.

## Pattern

### Local Schema Rules

- Include `declare(strict_types=1);`.
- Use anonymous classes: `return new class () extends Migration {`.
- Type migration methods and schema closures with `: void`.
- Omit `down()` when the migration set intentionally omits rollback methods.
- Put `id()` first.
- Add `caseInsensitiveText('public_id')->unique()` for routeable, interface-addressed, or API-exposed domain entities when following current domain-table migrations.
- Add a PostgreSQL `CHECK (public_id ~* '^[a-z0-9]{10}$')` constraint for new public-ID domain tables when sibling migrations do.
- Do not add `public_id` to framework, package, pivot, token, queue, cache, or one-off auth tables unless the table is routeable or publicly addressed.
- Add ownership/reference columns before descriptive fields.
- Use `foreignId('..._id')->index()` for relationship columns.
- Do not call `constrained()`, `foreign()`, `references()`, `cascadeOnDelete()`, or other database FK helpers unless the existing repository explicitly uses DB-level FKs.
- Use `timestamps()` consistently; add `softDeletes()` for entities whose deletes are reversible or whose uniqueness should ignore deleted rows.
- Prefer `timestamp(...)->nullable()` for nullable lifecycle timestamps such as `deactivated_at`.
- For pivot tables, include an `id()`, indexed relation columns, timestamps when sibling pivots do, and a compound `unique([...])` for the pair or tuple.
- For framework- or package-owned morph tables such as media and tokens, follow
  the owning package's shape and keep relation columns indexed through
  `morphs(...)`.
- For nullable geographic coordinates, use `decimal(..., 10, 7)` and database range checks: latitude allows `NULL` or `BETWEEN -90 AND 90`; longitude allows `NULL` or `BETWEEN -180 AND 180`.
- For nullable column pairs that must be both `NULL` or both non-`NULL`, add a named PostgreSQL `CHECK` for that all-or-none invariant and prove both invalid directions through direct persistence tests.
- Use enum-backed string columns with explicit lengths for ISO-like values, for example `string('region_code', 2)` or `string('unit_code', 3)`.
- Use decimal precision intentionally, for example measurements as `decimal(..., 8, 4)` and amount bands as `decimal(..., 8, 2)`.
- Use database defaults only when sibling migrations do; otherwise mirror defaults on the model `$attributes` and factory.
- For soft-deleted domain tables with business uniqueness, prefer partial unique indexes scoped with `WHERE deleted_at IS NULL`. In current local usage, `active` index names mean non-soft-deleted; do not add `deactivated_at IS NULL` unless the live invariant explicitly excludes deactivated rows.

Pivot table pattern:

```php
Schema::create('actor_parent_record', function (Blueprint $table): void {
    $table->id();

    $table->foreignId('actor_id')->index();
    $table->foreignId('parent_record_id')->index();

    $table->timestamps();

    $table->unique(['actor_id', 'parent_record_id']);
});
```

## Related References

- [`../README.md`](../README.md)
