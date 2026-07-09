# Migrations and Schema

## Purpose

Route migration and schema-backed-column work to the smallest applicable
pattern leaves.

## When To Use

Use for files under `database/migrations/**` and application changes that alter
schema-backed columns or constraints.

## Required Pattern

### Reference Map

- [`patterns/discovery.md`](patterns/discovery.md): Discovery.
- [`patterns/file-shape.md`](patterns/file-shape.md): File Shape.
- [`patterns/local-schema-rules.md`](patterns/local-schema-rules.md): Local Schema Rules.
- [`patterns/postgresql-patterns.md`](patterns/postgresql-patterns.md): PostgreSQL Patterns.
- [`patterns/anti-patterns.md`](patterns/anti-patterns.md): Anti-Patterns.

## Coverage Expectations

Read the affected migration and equivalent siblings. Cover the behavior in the
suite that owns it. A database-backed invariant needs direct persistence
coverage; add request/controller validation coverage only when the HTTP input
contract owns the same rejection.

## Do Not

- Do not infer rollback, foreign-key, public-ID, or constraint conventions from
  generic Laravel guidance.

## Related References

- [`references/app/Models/README.md`](../../app/Models/README.md)
- [`references/database/factories/README.md`](../factories/README.md)
- [`references/tests/Integration/Models/README.md`](../../tests/Integration/Models/README.md)
