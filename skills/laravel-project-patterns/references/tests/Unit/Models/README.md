# tests/Unit/Models

## Purpose

This reference defines conventions for `tests/Unit/Models`.

## When To Use

Use this reference when your task matches this path and you need to follow its local conventions.

## Required Pattern

Use `tests/Unit/Models/<Model>Test.php` for model-level behavior that does not need a persisted relationship graph.

### Focused References

- [Class-Local Model Contracts](patterns/class-local-contracts.md): Use this leaf for class-local model shape, casts, traits, defaults, and pure helpers.
- [Prunable And Config Predicates](patterns/prunable-and-config.md): Use this leaf for focused prunable selection and configuration-driven predicates.
- [Unit To Integration Boundary](patterns/integration-boundary.md): Use this leaf to decide when model behavior requires the integration suite.

## Coverage Expectations

Use this path for class-local model contracts only. If the assertion needs `createOne()` for a saved graph, first check whether it is actually system behavior; otherwise it probably belongs in a controller/resource/action test or should not be added.

## Do Not

- Do not contradict the skill non-negotiables or project conventions.

## Related References

- [`references/app/Models/README.md`](../../../app/Models/README.md)
- [`references/tests/Integration/Models/README.md`](../../Integration/Models/README.md)
