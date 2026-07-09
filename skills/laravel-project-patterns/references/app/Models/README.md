# Models and Relationships

## Purpose

This reference defines project conventions for Eloquent models, relationships, casts, accessors, route keys, and model PHPDoc.

## When To Use

Use this reference when creating or changing Eloquent models, traits, casts, accessors, model relationships, route-key behavior, or model-focused tests.

## Required Pattern

Use this reference when creating or changing schema-backed Eloquent models.

### Reference Map

- [`patterns/model-shape.md`](patterns/model-shape.md): Model Shape.
- [`patterns/core-rules.md`](patterns/core-rules.md): Core Rules.
- [`patterns/relationships.md`](patterns/relationships.md): Relationships.
- [`patterns/local-query-scopes.md`](patterns/local-query-scopes.md): Local Query Scopes.
- [`patterns/route-keys-and-public-ids.md`](patterns/route-keys-and-public-ids.md): Route Keys and Public IDs.
- [`patterns/domain-constraints.md`](patterns/domain-constraints.md): Domain Constraints.
- [`patterns/phpdoc-coverage-and-test-suite-split.md`](patterns/phpdoc-coverage-and-test-suite-split.md): PHPDoc Coverage; Test Suite Split.

## Coverage Expectations

Read the live model, migration, factory, and sibling model files for the touched area. Cover behavior in the suite or reference that owns that surface.

## Do Not

- Do not contradict the skill non-negotiables or project conventions.
- Do not add relationship smoke tests just to prove framework wiring.

## Related References

- [`references/app/Models/Concerns/README.md`](Concerns/README.md)
- [`references/app/Models/World/README.md`](World/README.md)
- [`references/tests/Integration/Models/README.md`](../../tests/Integration/Models/README.md)
- [`references/tests/Unit/Models/README.md`](../../tests/Unit/Models/README.md)
