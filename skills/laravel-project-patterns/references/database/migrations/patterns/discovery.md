# Discovery

## When To Use

Read this focused reference when the task involves discovery.

## Pattern

### Discovery

Before writing a migration:

```bash
rg --files database/migrations tests/migrations | sort
rg "function down|constrained\\(|foreign\\(|references\\(|cascadeOn|dropForeign" database/migrations tests/migrations
rg "foreignId\\(" database/migrations
```

When Laravel Boost is available, inspect the schema with `database-schema` summary first, then filtered details for affected tables. Confirm indexes and foreign keys in the live schema instead of inferring from code only.

## Related References

- [`../README.md`](../README.md)
