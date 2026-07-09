# app/Enums

## Purpose

This reference defines conventions for enums under `app/Enums`.

## When To Use

Use this reference when your task matches this path and you need to follow its local conventions.

## Required Pattern

Use `app/Enums` for backed enums and enum helpers used by validation, resources, factories, forms, model casts, flash payloads, and generated-value configuration.

### Reference Map

- [`patterns/enum-shape.md`](patterns/enum-shape.md): Enum Shape.
- [`patterns/simple-values-enum-example.md`](patterns/simple-values-enum-example.md): Simple Values Enum Example.
- [`patterns/translated-option-enum-example.md`](patterns/translated-option-enum-example.md): Translated Option Enum Example.
- [`patterns/helper-enum-example.md`](patterns/helper-enum-example.md): Helper Enum Example.
- [`patterns/test-mapping.md`](patterns/test-mapping.md): Test Mapping.

## Coverage Expectations

Read the live enum and siblings exposing the same traits and public contract.
Cover only outputs the enum or its consumer exposes.

## Do Not

- Do not contradict the skill non-negotiables or project conventions.

## Related References

- [`references/tests/Unit/Enums/README.md`](../../tests/Unit/Enums/README.md)
- [`references/tests/Feature/Http/Controllers/README.md`](../../tests/Feature/Http/Controllers/README.md)
- [`references/app/functions.php.md`](../functions.php.md)
