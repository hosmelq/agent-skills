# Scoped `exists` and `unique` Validation Snippets

## Purpose

This reference defines controller feature-test snippets for scoped `exists` and `unique` validation.

## When To Use

Use this reference when validation rules depend on the current `Workspace`, direct parent, ancestor chain, public IDs, soft deletion, inactive reservation, or update `ignore(...)`.

## Required Pattern

Keep scoped validation tests close to `store` or `update`. Use the base dataset for malformed values and focused `it(...)` tests for cases that need persisted rows or a parent chain.

### Reference Map

- [`scoped-exists-and-unique/scoped-exists-example.md`](scoped-exists-and-unique/scoped-exists-example.md): Scoped `exists` Example.
- [`scoped-exists-and-unique/parent-dependent-exists.md`](scoped-exists-and-unique/parent-dependent-exists.md): Parent-Dependent `exists`.
- [`scoped-exists-and-unique/scoped-unique-on-store.md`](scoped-exists-and-unique/scoped-unique-on-store.md): Scoped `unique` on Store.
- [`scoped-exists-and-unique/scoped-unique-on-update-with-ignore.md`](scoped-exists-and-unique/scoped-unique-on-update-with-ignore.md): Scoped `unique` on Update with `ignore(...)`.

## Coverage Expectations

For scoped `exists`, cover out-of-scope records, soft-deleted related records when applicable, inactive related records when selectors are active-only, and current-record continuity exceptions when implemented.

For scoped `unique`, cover same-scope duplicate failure, allowed cross-scope duplicate when applicable, update current-record `ignore(...)`, soft-deleted reuse when the rule or index excludes trashed rows, and inactive-record reservation when the rule still counts non-soft-deleted rows.

Do not turn inactive uniqueness reservation into an active-only selector rule. Uniqueness asks whether a value is still reserved; selectable relationships ask whether a related record may be chosen.

Range or overlap validation belongs here only when the Form Request owns the rule. If it needs locks or transactional state, prove it in action integration tests and keep controller coverage for mapped action exceptions.

## Do Not

- Do not contradict the skill non-negotiables or project conventions.
- Do not drop applicable validation coverage when adapting examples.
- Do not use real module, route, or entity names in examples.

## Related References

- [`references/tests/Feature/Http/Controllers/README.md`](../README.md)
