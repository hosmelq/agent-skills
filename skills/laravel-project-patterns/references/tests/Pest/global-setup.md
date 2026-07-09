# Pest Global Setup

## When To Use

Use this leaf for global suite setup and shared helper registration.

## Pattern

### Global Setup

- Tests extend the project `Tests\TestCase`.
- The global `beforeEach` freezes time to the current second.
- Vite is disabled globally for test requests.
- The active suites are `Feature`, `Integration`, and `Unit`.

Required entries:

- global `pest()->extend(TestCase::class)` setup for `Feature`, `Integration`, and `Unit`;
- global `assertDatabaseLockedForUpdate(...)` helper for action integration tests that must prove `lockForUpdate()`;
- global `login(...)` helper for authenticated tests;
- `TestResponse::macro('assertToast', ...)` for redirect toast assertions.


### Implications

- Do not add duplicate helper functions inside individual test files.
- Do not manually call `withoutVite()` in ordinary tests.
- Use frozen time directly in date assertions unless a test explicitly changes time.

## Related References

- [Parent router](../Pest.md)
