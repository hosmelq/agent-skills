# PostgreSQL Patterns

## When To Use

Read this focused reference when the task involves postgresql patterns.

## Pattern

### PostgreSQL Patterns

If the app uses `tpetry/laravel-postgresql-enhanced` or `citext`:

- Use `Tpetry\PostgresqlEnhanced\Schema\Blueprint` and facade only for enhanced column types such as `caseInsensitiveText`.
- Keep regular Laravel `Blueprint` and `Schema` for ordinary tables.
- Use `DB::statement(<<<'SQL' ... SQL);` for partial unique indexes.
- Keep index names explicit and descriptive, especially for partial unique indexes such as non-deleted email, non-deleted region, or soft-delete-aware normalized code constraints.
- Use `DB::statement(<<<'SQL' ... SQL);` for additive check constraints on existing tables when no column changes are needed.

Soft-delete-aware unique index pattern:

```php
DB::statement(<<<'SQL'
    CREATE UNIQUE INDEX child_records_active_name_unique
    ON child_records (parent_record_id, normalized_name)
    WHERE deleted_at IS NULL
SQL);
```

Soft-delete-aware default flag pattern:

```php
DB::statement(<<<'SQL'
    CREATE UNIQUE INDEX child_records_active_default_unique
    ON child_records (parent_record_id)
    WHERE deleted_at IS NULL AND is_default = true
SQL);
```

Nullable coordinate range check pattern:

```php
DB::statement(<<<'SQL'
    ALTER TABLE child_records
    ADD CONSTRAINT child_records_latitude_range_check
    CHECK (latitude IS NULL OR latitude BETWEEN -90 AND 90)
SQL);
```

Paired nullable-column check pattern:

```php
DB::statement(<<<'SQL'
    ALTER TABLE child_records
    ADD CONSTRAINT child_records_value_pair_check
    CHECK (
        (start_value IS NULL AND end_value IS NULL)
        OR (start_value IS NOT NULL AND end_value IS NOT NULL)
    )
SQL);
```

Half-open interval exclusion pattern:

```php
DB::statement(<<<'SQL'
    ALTER TABLE child_records
    ADD CONSTRAINT child_records_active_amount_range_exclude
    EXCLUDE USING gist (
        parent_record_id WITH =,
        numrange(minimum_amount, maximum_amount, '[)') WITH &&
    )
    WHERE (deleted_at IS NULL)
SQL);
```

Use `[)` so an upper endpoint may touch the next lower endpoint without
overlapping. A nullable upper bound remains open-ended. Name the constraint,
scope every ownership dimension that defines independence, and include the
same soft-delete predicate as the business invariant. Enable `btree_gist` in a
separate extension migration before using scalar equality alongside a range.

When adding or changing a partial unique index that enforces a business invariant, add focused coverage for direct persistence in the model integration suite so the new or changed database constraint is proven independently of controller validation.

When a database check or exclusion constraint backs request validation, add
the same direct persistence coverage with the constraint name, for example a
model integration test expecting `QueryException` containing
`child_records_latitude_range_check` or
`child_records_active_amount_range_exclude`. Include overlap, adjacency,
open-ended, soft-deleted reuse, and cross-scope cases for a range exclusion.

## Related References

- [`../README.md`](../README.md)
