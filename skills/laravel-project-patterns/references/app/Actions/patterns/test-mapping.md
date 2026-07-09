# Test Mapping

## When To Use

Read this focused reference when the task involves test mapping.

## Pattern

### Test Mapping

- Action behavior is covered through `tests/Integration/Actions` when the action itself owns branching, persistence, retries, or collaborator coordination.
- Resolve actions from the container in tests.
- Mock injected collaborators through the container.
- Instantiate Data inputs in action tests with `Input::from([...])` so casting, name mapping, and `Optional` behavior are exercised at the action boundary.
- Keep action tests ordered from guard/failure/domain-exception cases to success cases. For create inputs, add a required-only or omitted-fields success case only when omitted `Optional` fields or model defaults are behavior owned by the action.
- Name the primary create success case after the created record, such as `creates a child record`. Keep parent or owner qualifiers out of the name unless parent scope is the behavior being proved; assert ordinary parent persistence in the test body instead.
- When an exceptional action owns a documented lock, test the business guard that requires serialization. Do not add a query-shape test merely because the action emits `FOR UPDATE`.
- Do not add a lock or lock assertion because the scenario catalog contains a conditional lock row. The live action must already participate in the complete shared-root protocol described above.
- Do not add ownership-mismatch or stale-binding integration tests when the entrypoint owns those boundaries. Test action-owned behavior: persistence, domain exceptions, idempotent lifecycle transitions, and side effects.
- Cover the applicable behavior owned by the action: success, cleanup, retry collisions, `Workspace` or parent isolation only when the action owns that invariant, soft-delete or active-state semantics, and domain exception limits when those branches exist.
- When a transactional or dependent-record guard moves out of a Form Request, the action owns the guard test. Keep the controller feature test for the HTTP entry point and exception-to-validation mapping; do not delete it as duplicate coverage.

## Related References

- [`../README.md`](../README.md)
