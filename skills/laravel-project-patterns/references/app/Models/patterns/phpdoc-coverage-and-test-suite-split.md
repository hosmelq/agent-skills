# PHPDoc Coverage and Test Suite Split

## When To Use

Read this focused reference when the task involves phpdoc coverage and test suite split.

## Pattern

### PHPDoc Coverage

Keep model PHPDoc useful for static analysis:

- Include scalar DB columns as `@property-read`.
- Include nullable values as `null|Type`.
- Include enum and value object casts using their class names.
- Include timestamps and soft-delete timestamps.
- Include relationship properties with `Collection<int, Model>` or nullable/single model types.

Do not add docblock entries for imagined relationships or columns.

### Test Suite Split

Use `references/tests/Unit/Models/README.md` for class-local contracts and `references/tests/Integration/Models/README.md` for the canonical persisted model boundary.

Unit model tests cover local class contracts such as traits, casts, defaults, and pure helpers. Integration model tests cover persisted domain behavior such as default-child selection, observer effects, slug stability, route key persistence, and `Workspace`-scoped system rules.

## Related References

- [`../README.md`](../README.md)
