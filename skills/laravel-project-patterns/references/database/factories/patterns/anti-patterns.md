# Anti-Patterns

## When To Use

Read this focused reference when the task involves anti-patterns.

## Pattern

### Anti-Patterns

- Do not manually create parent records in every test when the factory can express the relationship.
- Do not create records with mismatched ownership IDs unless the test is explicitly about invalid or unrelated data.
- Do not leave nullable optional fields randomly non-null if tests need predictable default behavior; default them to `null` and add a state for non-null variants when useful.
- Do not use `recycle()` as a shortcut to hide ownership setup or avoid asserting the relationship graph under test.

Factory guidance should not reintroduce model-integration relationship smoke tests. Use `references/tests/Integration/Models/README.md` for the canonical persisted model boundary.

## Related References

- [`../README.md`](../README.md)
