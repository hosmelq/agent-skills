# Store / Update Validation Patterns

## When To Use

Read this focused reference when the task involves store / update validation patterns.

## Pattern

### Store / Update Validation Patterns

Use focused datasets and named tests near `store` and `update` actions.

- Dataset base validation covers required fields, types, arrays, nested fields, formats, and invalid values.
- Update tests prove omitted fields are allowed only when the update contract allows them.
- Enum, string length, numeric, integer, decimal precision, min/max, and same-payload comparisons are dataset cases.
- Stored-value comparisons on update are named tests.
- Paired fields with `required_with` cover both directions.
- Public IDs are validated in the current `Workspace`/parent scope and resolved to internal IDs only for persistence.
- Nullable relationships get success tests for assignment and clearing when the controller owns the behavior.
- Request-owned dependent-record prohibitions stay in Form Request validation tests.
- Action-owned dependent-record guards are mocked in controller tests only for exception-to-validation mapping and are fully proven in action integration tests.
- Scoped uniqueness covers same-scope failure, current-record ignore on update, allowed cross-scope reuse, soft-deleted reuse when permitted, and inactive reserved rows when still counted.
- Normalized public fields should be tested using the submitted public field.
- Range/overlap rules stay in controller validation only when the Form Request owns them. If the rule needs locks, transactional reads, or dependent rows that can change concurrently, move the guard to the action and keep the controller test focused on mapped validation.

## Related References

- [`../pattern-catalog.md`](../pattern-catalog.md)
