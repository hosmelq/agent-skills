# Core Contract Map

## When To Use

Use this map to select one cross-cutting contract.

## Pattern

- [`core/README.md`](../core/README.md): choose one cross-cutting contract.
  - [`core/code-and-schema.md`](../core/code-and-schema.md): repository safety, finality, generated files, migrations, and unguarded models.
  - [`core/test-design-and-style.md`](../core/test-design-and-style.md): test ownership, persistence assertions, fixtures, and Pest style.
  - [`core/http-and-request-boundaries.md`](../core/http-and-request-boundaries.md): controller entry points, mocks, mapping, binding, and request validation.
  - [`core/actions-and-concurrency.md`](../core/actions-and-concurrency.md): business inputs, re-query boundaries, constraints, and locks.
  - [`core/completion-checklist.md`](../core/completion-checklist.md): final cross-surface verification.

## Related References

- [Parent router](../MAP.md)
