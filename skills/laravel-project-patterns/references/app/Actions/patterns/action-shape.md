# Action Shape

## When To Use

Read this focused reference when the task involves action shape.

## Pattern

### Action Shape

- Use strict types, constructor injection, and explicit return types.
- Prefer a public `handle(...)` entrypoint for domain actions, while preserving existing framework contract method names such as `create`, `reset`, and `update`.
- Framework contract actions may keep framework-owned validation, validation bags, notification side effects, and `forceFill(...)->save()` when that is the package contract pattern. Normal app-owned actions should use `$model->update([...])` or relationship creates.
- For action input that needs typed transformation, omitted-field semantics, or persistence-ready field mapping, prefer a dedicated input object over passing raw arrays through the action boundary.
- Keep retry limits, expiration windows, and similar invariants as typed constants on the action.
- Use transactions when the action coordinates cleanup plus creation, lifecycle changes with guards, default selection, pivot changes, or multiple persisted side effects.
- A transaction makes its writes atomic; it does not imply that the action needs a row lock.
- Default to no explicit row lock. Active-state checks, move/order behavior, initial/default selection, and multiple writes are not sufficient reasons to add `lockForUpdate()`.
- Prefer database constraints for business invariants that PostgreSQL can enforce. Add a lock only for a single-row consume/recheck transition whose competing consumers all use that path, or for an existing documented cross-row protocol that cannot reasonably be expressed in the database after enumerating every competing action and confirming that all of them lock the same stable row in the same order before dependent reads or writes.
- Keep helpers private unless another application surface, framework contract, stub, or trait actually consumes or exposes them.

## Related References

- [`../README.md`](../README.md)
