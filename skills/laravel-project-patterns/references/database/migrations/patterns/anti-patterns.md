# Anti-Patterns

## When To Use

Read this focused reference when the task involves anti-patterns.

## Pattern

### Anti-Patterns

- Do not add a generated `down()` just because Laravel docs show one.
- Do not add DB-level foreign key constraints in an app that uses indexed relation columns only.
- Do not rename existing columns or indexes for cleanup while adding unrelated schema.
- Do not make a new model routeable by slug unless sibling models and routes show slug route keys for that concept.

## Related References

- [`../README.md`](../README.md)
