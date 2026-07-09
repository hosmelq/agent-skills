# Tests

## When To Use

Read this focused reference when the task involves tests.

## Pattern

### Tests

- Cover provider-facing behavior through the surface it affects: architecture tests, middleware feature tests, controller feature tests, model tests, or integration tests.
- Do not add provider tests that only duplicate framework wiring unless the wiring is the contract.
- For container bindings, prefer testing the consuming action unless the binding alias itself is the contract.

## Related References

- [`../README.md`](../README.md)
