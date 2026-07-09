# Database Patterns

## Purpose

Route database work to only the migration and factory branches touched by the
task.

## When To Use

Use this router for files under `database/migrations/**` or
`database/factories/**`.

## Required Pattern

- Load [`migrations/README.md`](migrations/README.md) for schema discovery,
  migration shape, indexes, public IDs, soft deletes, constraints, and
  PostgreSQL-specific patterns.
- Load [`factories/README.md`](factories/README.md) for factory defaults,
  relationship APIs, ownership coherence, states, and hooks.

## Coverage Expectations

Read the live migration or factory and equivalent siblings before choosing a
focused pattern leaf.

## Do Not

- Do not preload both branches when only one database surface is touched.
- When one change owns both schema and fixture behavior, route and read each
  branch separately.
- Do not infer foreign-key or rollback conventions from generic Laravel habits.

## Related References

- [`references/MAP.md`](../MAP.md)
- [`references/core/code-and-schema.md`](../core/code-and-schema.md)
