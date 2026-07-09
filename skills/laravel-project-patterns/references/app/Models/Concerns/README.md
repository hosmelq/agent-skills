# app/Models/Concerns

## Purpose

This reference defines project conventions for reusable Eloquent behavior shared by models.

## When To Use

Use this reference when creating or changing a model concern, shared model scope, generated route-key behavior, public ID finder, or reusable lifecycle helper.

## Required Pattern

Use `app/Models/Concerns` for reusable Eloquent behavior shared by models.

### Concern Shape

- Keep concerns narrow and model-focused.
- Use typed methods and relationship-safe query logic.
- Define local Eloquent scopes with `#[Scope]` on protected methods.
- Prefer `$builder` as the first parameter, place dynamic scope parameters after it, and return `void` when mutating the builder in place.
- Use `$builder->qualifyColumn(...)` inside reusable concern scopes so constraints stay unambiguous when callers compose the scope with joins or relationship queries.
- Do not use legacy `scopeFoo(...)` methods for new concerns unless a sibling concern already uses that older pattern.
- If a concern changes route-key behavior, public IDs, generated IDs, validation, or finder methods, treat that as a cross-model contract.

### Test Mapping

- Persisted concern APIs are covered through `tests/Integration/Models/Concerns`.
- Route-binding behavior from a concern is covered through `tests/Feature/Models/Concerns`.
- Use test support models when the behavior is generic.

### Focused References

- [Deactivation Concern](patterns/deactivation.md): Use this leaf for the reusable deactivation concern contract.
- [Public ID Concern](patterns/public-id.md): Use this leaf for public ID generation, finder, and route-key behavior.

## Coverage Expectations

Read the live concern, consuming models, migrations, and relevant tests. Cover cross-model behavior in the suite that owns the contract.

## Do Not

- Do not contradict the skill non-negotiables or project conventions.
- Do not move workflow-specific authorization, validation, or locking rules into a generic concern.

## Related References

- [`references/app/Models/README.md`](../README.md)
- [`references/tests/Integration/Models/Concerns/README.md`](../../../tests/Integration/Models/Concerns/README.md)
- [`references/tests/Feature/Models/Concerns/README.md`](../../../tests/Feature/Models/Concerns/README.md)
