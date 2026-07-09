# Persisted System Logic And Database Invariants

## When To Use

Use this leaf for persisted model system behavior and direct database invariants.

## Pattern

### System Logic Tests

Only keep model integration tests that prove persisted system behavior. Good examples include:

- observer-managed state, such as default child selection;
- model methods that require persisted records or saved relationship state;
- slugs, route keys, state transitions, and persisted side effects;
- domain-scoped relationships where the scope itself is business logic, such as selecting only the default child.
- local query scopes only when the scope itself owns system behavior that is not already covered by a public method, finder, controller path, or broader persisted behavior.
- `Workspace`/current-`Workspace` state transitions that cannot be proven on an in-memory model.
- database-enforced business invariants, such as a soft-delete-aware unique constraint that must block direct persistence outside the controller path.
- database check constraints that protect domain ranges, such as latitude and longitude geographic bounds.

For database-enforced uniqueness, assert the direct write is blocked without going through controller validation:

```php
it('enforces non-deleted code uniqueness per Workspace', function (): void {
    $record = Record::factory()->createOne([
        'normalized_code' => 'ABC123',
    ]);

    expect(fn () => Record::factory()->recycle($record->workspace)->createOne([
        'normalized_code' => 'ABC123',
    ]))->toThrow(QueryException::class);
});
```

When adding or changing a database constraint with named failure modes, assert the constraint name in the exception message so the test proves the intended invariant:

```php
it('enforces the latitude geographic range at the database level', function (): void {
    expect(fn () => ParentRecord::factory()->createOne([
        'latitude' => 91,
        'longitude' => 0,
    ]))->toThrow(QueryException::class, 'parent_records_latitude_range_check');
});
```

For non-soft-deleted name uniqueness, mirror the actual database predicate:

- duplicate non-soft-deleted records in the same `Workspace` throw the named unique constraint;
- soft-deleted records can be replaced when the index excludes deleted rows;
- deactivated but not deleted records remain reserved when the index does not exclude `deactivated_at`.

Do not add tests that only prove Laravel relationship loading or schema wiring, such as:

- FK/ID equality between related models;
- `->toBeInstanceOf(RelatedModel::class)`;
- `->toHaveCount(n)` because a factory created `n` children;
- mirrored parent/child relationship tests without distinct system behavior.
- generic index or column existence checks with no domain behavior attached.
- direct tests for simple query-scope wrappers when another tested public API already proves the same constraint.


### Related Model Rule

When adding or changing a relationship, update related model tests only when the change introduces or alters system behavior. Do not add paired relationship tests solely to prove both Eloquent relationship directions load.

## Related References

- [Parent router](../README.md)
