# File Shape

## When To Use

Read this focused reference when the task involves file shape.

## Pattern

### File Shape

- Import the action, models, exceptions, enum collaborators, and `Mockery\MockInterface` when mocking container services.
- Resolve handle-based actions from the container with `resolve(ActionClass::class)->handle(...)`.
- Pass typed action inputs with `<ActionInput>::from([...])` when the action accepts a Data input. This keeps name mapping, casts, and `Optional` behavior in the test path.
- Use factories for persisted setup.
- Use `assertDatabaseHas()` when the durable database side effect is the contract.
- Factory creation, casts, accessors, initial timestamps, and first relationship
  access do not justify a refresh. Use a separate `$model->refresh();` only
  after an external database mutation when the contract is specifically the
  same loaded instance observing reloaded Eloquent state, including
  dirty/original behavior. Never embed `refresh()` or `fresh()` inside
  `expect(...)`.
- Do not duplicate ordinary field assertions with both `assertDatabaseHas()` and a refreshed-model expectation. Choose the assertion that matches the contract.
- Order action tests from guard/failure/domain-exception cases to success cases. Put the primary success path before extra success variants when no failure cases exist.
- Name the primary create success case after the created record, such as `creates a child record`. Do not append the parent or owner merely because the row persists its ID; use scope qualifiers only for behavior such as cross-parent isolation or an active-parent guard.
- Prefer direct database-constraint coverage for invariants enforced by PostgreSQL. Do not introduce a lock or lock test from an action category, transaction, active-state check, move/order operation, initial/default selection, or catalog row. When the live action already participates in a documented shared-root protocol with every competing action, test the business guard that requires serialization instead of only asserting the emitted SQL shape.
- Match tests to the action boundary. Do not add ownership-mismatch tests when the entrypoint's scoped bindings and policies own that boundary. Test action-owned persistence, domain exceptions, idempotent lifecycle behavior, and side effects.
- Create actions may test `Workspace`/parent persistence because the parent model is business input for the new row.
- Test ownership, scoped binding, authorization, and soft-delete lookup failures at the entrypoint instead of recreating the route hierarchy in action tests.
- Framework contract actions may use contract method names such as `create(...)`, `reset(...)`, or `update(...)` instead of `handle(...)`. Test those methods directly, including validation exceptions, validation bags, notifications, and persistence when the contract action owns them.

## Related References

- [`../README.md`](../README.md)
