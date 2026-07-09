# Core Contracts

## Purpose

Route cross-cutting rules that must remain consistent across application,
database, resource, and test references.

## When To Use

Read this router after the live repository and nearest equivalent siblings, then select
only the focused contract needed for the change. Domain routers remain the
source for complete examples.

## Required Pattern

- Use [`code-and-schema.md`](code-and-schema.md) for finality, generated Laravel
  files, migrations, and globally unguarded models.
- Use [`test-design-and-style.md`](test-design-and-style.md) for test ownership,
  persistence assertions, fixtures, Pest chains, and affected test surfaces.
- Use [`http-and-request-boundaries.md`](http-and-request-boundaries.md) for
  controller entry-point coverage, delegated-action mocks, Request-to-Input
  mapping, response variables, scoped binding, and request validation.
- Use [`actions-and-concurrency.md`](actions-and-concurrency.md) for action
  business inputs, model re-query boundaries, database constraints, locks, and
  rejected-lock alternatives.
- Use [`completion-checklist.md`](completion-checklist.md) only when verifying a
  finished change across every touched surface.

## Coverage Expectations

These leaves preserve the global contracts. Load the matching domain router for
the full implementation or test examples and apply a rule only when the live
surface makes it relevant.

## Do Not

- Do not preload every core leaf.
- Do not treat a focused example as proof that its behavior exists everywhere.
- Do not replace live repository evidence with a generic rule.

## Related References

- [`references/MAP.md`](../MAP.md)
- [`references/app/README.md`](../app/README.md)
- [`references/database/README.md`](../database/README.md)
- [`references/resources/README.md`](../resources/README.md)
- [`references/tests/README.md`](../tests/README.md)
