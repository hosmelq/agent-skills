# Core Rules

## When To Use

Read this focused reference when the task involves core rules.

## Pattern

### Core Rules

- Include strict types and `@extends Factory<Model>`.
- Use model factories for required parent IDs: `'parent_record_id' => ParentRecord::factory()`.
- Group relationship/ownership IDs before regular attributes, separated by a blank line.
- Factory defaults should create valid, ordinary active records.
- In tests, do not pass attributes that the factory already generates unless that exact value is part of the assertion, query, validation failure, redirect target, or serialized contract under test.
- Use enum instances or enum values consistently with sibling factories and casts.
- Prefer realistic fake data that satisfies validation and database constraints.
- Prefer `fake()->unique()` where uniqueness is required by schema or behavior. Fields with app-level uniqueness expectations may also use it to keep fixtures stable even when the database does not enforce a matching unique constraint.
- When a factory repeatedly needs an expensive deterministic default such as a hashed password, a static cache property is acceptable if sibling factories use it and the cached value is not test-specific.
- For partial unique constraints that tests may hit repeatedly in the same owner scope, make the constrained factory value unique by default, such as `fake()->unique()->word()` for active names or normalized codes. Override explicit duplicates only in tests that assert the uniqueness behavior.
- Use `createOne()` in tests for single persisted records; use `count(...)->create()` only when multiple records are the subject of the assertion.
- Use `createOne()` for single model instances that are passed to route helpers, route model binding, or controller redirect assertions, including mocked action return values used to build redirect routes. Do not set generated route keys such as `public_id`, `slug`, or generated codes unless the literal value is asserted; factories generate valid route keys. Set only relationships and non-generated attributes required by that contract and, when the persisted row can affect validation, choose non-conflicting domain values. Use `makeOne()` only when the test explicitly needs an unsaved model that is not used as a route parameter.
- Use state methods for meaningful domain variants such as `deactivated()`, `default()`, `expired()`, `used()`, `openEnded()`, or `roundUp()`.
- Return `static` from state methods unless sibling factories use `self`.
- Default lifecycle columns such as `deactivated_at` to `null` and add a named state like `deactivated()` instead of making inactive records randomly.
- When one fake value constrains another, compute them together so defaults always pass validation and database constraints. Examples include minimum/maximum pairs, range bands, and latitude/longitude values generated with `fake()->latitude()` and `fake()->longitude()`.
- For address-like factories, use the local region-data concern when siblings do so region, subdivision, locality, and contact-number values remain coherent; add a deterministic state such as `forRegion(RegionCode $regionCode)` when callers need a fixed region.

State method examples:

[Factory State Methods](core-rules/state-methods.md)

## Related References

- [`../README.md`](../README.md)
