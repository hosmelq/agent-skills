# tests/Integration/Models/Concerns

## Purpose

This reference defines conventions for persisted model concern tests under `tests/Integration/Models/Concerns`.

## When To Use

Use this reference when your task matches this path and you need to follow its local conventions.

## Required Pattern

Use `tests/Integration/Models/Concerns/<Concern>Test.php` for persisted behavior provided by reusable model concerns.

### File Shape

- Import only the exception types, collaborators, and test support models needed by the concern under test.
- Persist a generic test support model through `ExampleModel::query()->create()`.
- Assert generated identifiers are strings and have the expected length when the concern generates identifiers.
- Assert case-insensitive finder behavior when the concern stores public IDs in case-insensitive columns.
- Use `expect(fn () => ...)->toThrow(...)` for exception cases.

### Split From Feature Tests

This path proves the concern's persisted model API. Use `tests/Feature/Models/Concerns` for route model binding behavior through HTTP and routing middleware.

### System-Logic-Only Policy

This path inherits the persisted model boundary from `references/tests/Integration/Models/README.md`; keep coverage focused on reusable concern behavior that needs saved records.

### Focused References

- [Persisted Deactivation Concern](patterns/deactivation.md): Use this leaf for the reusable persisted deactivation API.
- [Persisted Public ID Concern](patterns/public-ids.md): Use this leaf for public ID creation and finder behavior.

## Coverage Expectations

Cover only persisted reusable-concern behavior in this path. Route binding behavior for the same concern belongs in `tests/Feature/Models/Concerns`.

## Do Not

- Do not contradict the skill non-negotiables or project conventions.
- Do not add generic Laravel relationship mechanic assertions (loading, related-model type checks, FK/ID equality, or factory/count smoke checks).

## Related References

- [`references/tests/Integration/Models/README.md`](../README.md)
- [`references/tests/Unit/Models/README.md`](../../../Unit/Models/README.md)
