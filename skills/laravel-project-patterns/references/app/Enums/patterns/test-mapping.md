# Test Mapping

## When To Use

Read this focused reference when the task involves test mapping.

## Pattern

### Test Mapping

- Pure enum contracts are covered through `tests/Unit/Enums`.
- Every enum with `values()` should have an exact values test.
- When changing enum helper methods beyond `values()`, add focused assertions for the changed contract.
- When changing `Options`, translated option enums, or metadata properties, add options/translation-key assertions in the owning unit test or preserve the controller feature test that exposes `options()` as a prop contract. Current baseline enum tests may only assert `values()` when controller tests already protect the exposed option arrays.

## Related References

- [`../README.md`](../README.md)
