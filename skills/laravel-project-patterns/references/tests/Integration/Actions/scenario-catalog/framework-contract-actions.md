# Framework Contract Actions

## When To Use

Read this focused reference when the task involves framework contract actions.

## Pattern

### Framework Contract Actions

Keep this order when the action implements a framework method such as
`create(...)`, `reset(...)`, or `update(...)`:

| Order | Distinct test | Required proof |
| ---: | --- | --- |
| 1 | validates fields | exact alphabetical named dataset and exact error messages/bag |
| 2 | validates uniqueness or other contract-owned domain rule | direct contract method call and exact validation error |
| 3 | persists the successful contract operation | exact changed fields, verification/reset side effects, notification when owned, and unchanged unrelated fields |

## Related References

- [`../scenario-catalog.md`](../scenario-catalog.md)
