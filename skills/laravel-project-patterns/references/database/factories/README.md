# Factories

## Purpose

Route factory work to the smallest applicable pattern leaves.

## When To Use

Use for files under `database/factories/**` and factory behavior changed from
tests or application setup.

## Required Pattern

### Reference Map

- [`patterns/factory-shape.md`](patterns/factory-shape.md): Factory Shape.
- [`patterns/core-rules.md`](patterns/core-rules.md): Core Rules.
- [`patterns/coherent-reference-data.md`](patterns/coherent-reference-data.md): Coherent Reference Data.
- [`patterns/relationship-coherence.md`](patterns/relationship-coherence.md): Relationship Coherence.
- [`patterns/factory-relationship-apis.md`](patterns/factory-relationship-apis.md): Factory Relationship APIs.
- [`patterns/anti-patterns.md`](patterns/anti-patterns.md): Anti-Patterns.

## Coverage Expectations

Read the changed factory and equivalent sibling factories. Add coverage only
when the factory itself owns behavior that needs proof; using a factory in a
test does not create a factory-test requirement.

## Do Not

- Do not copy every catalog state into each factory.
- Do not hide ownership setup with convenience APIs.

## Related References

- [`references/app/Models/README.md`](../../app/Models/README.md)
- [`references/database/migrations/README.md`](../migrations/README.md)
- [`references/tests/Integration/Models/README.md`](../../tests/Integration/Models/README.md)
