# Success / Side-Effect Patterns

## When To Use

Read this focused reference when the task involves success / side-effect patterns.

## Pattern

### Success / Side-Effect Patterns

- Data input-backed action delegation changes the owner: controller tests own HTTP boundary, action invocation, redirect/toast/JSON response, and minimal input mapping; action integration tests own persistence, defaults, nullable clearing, generated values, locks, transactions, domain exceptions, and side effects.
- Actions receive only the models and inputs required by the operation. A create action may receive the direct business parent.
- Mocked action argument callbacks return booleans.
- Mocked create/store actions return persisted models only when the controller needs a route key.
- Do not configure a mock return value when the controller ignores the action result and redirects with an already-bound model.
- Controller-owned persistence uses database assertions for durable effects.
- Do not refresh a model created by a factory merely to assert its initial
  attributes, casts, accessors, or first relationship access. Use a separate
  `$model->refresh();` only when an external database mutation occurred and the
  behavior under test is the same loaded instance observing that change. Never
  embed `refresh()` or `fresh()` inside `expect(...)`.
- Public IDs submitted by forms persist as the resolved internal FK when the controller owns resolution.
- Default switching assertions belong to the suite that owns the side effect.
- Delete cleanup, soft delete, hard delete, or detach/reset assertions belong to the suite that owns the mutation.
- Web mutations assert named-route redirects and one-argument toast/flash output when emitted.
- JSON session success asserts token creation, identity linking, access-code usage, actor creation/reuse, and notification dispatch when those are part of the endpoint contract.

## Related References

- [`../pattern-catalog.md`](../pattern-catalog.md)
