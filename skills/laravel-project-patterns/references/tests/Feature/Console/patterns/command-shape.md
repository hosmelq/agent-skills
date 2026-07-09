# Console Command Test Shape

## When To Use

Use this leaf for ordinary console command feature-test structure.

## Pattern

### File Shape

- Import `Pest\Laravel\artisan`.
- Fake HTTP responses with `Http::fake()`.
- Override config values with `Config::set(...)` when the command reads config.
- Use framework facades for file assertions when sibling tests do.
- Assert command success with `artisan('command:name')->assertSuccessful()`.

## Related References

- [Parent router](../README.md)
