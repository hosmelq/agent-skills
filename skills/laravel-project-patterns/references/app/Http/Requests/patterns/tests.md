# Tests

## When To Use

Read this focused reference when the task involves tests.

## Pattern

### Tests

- Request behavior is normally covered through controller feature tests.
- Do not add unit tests for removed or absent request `input()` or `payload()` helpers. If a request no longer owns action input transformation, cover validation in controller feature tests and input/persistence behavior in action integration tests.
- Assert exact validation messages in datasets when sibling tests do.
- Keep dataset payloads minimal and targeted to the failing rule.
- Validation tests must authenticate an authorized in-scope actor and use route parameters that pass binding; otherwise `403` or `404` will mask the validation contract.

## Related References

- [`../README.md`](../README.md)
