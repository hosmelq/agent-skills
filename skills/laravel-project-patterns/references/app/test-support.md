# Application Test Support

## When To Use

Use this leaf when application code touches global test setup, authentication
helpers, external HTTP boundaries, or reusable test-support models and
migrations.

## Pattern

- Global Pest setup freezes time and disables Vite for Unit, Integration, and Feature suites.
- Use the shared login helper when authentication is needed.
- Use existing test support models/migrations for generic trait or package-support behavior instead of creating application-only fixtures.
- `Http::preventStrayRequests()` is active, so every external request in a test must be faked or mocked.
- Web controller tests must be compared against equivalent live nested resources before adding or deleting cases. A route with the same depth, binding ownership, middleware, transport, and response contract may define the applicable `404` boundaries; a merely deeper route does not.

For model integration test boundaries, load `references/tests/Integration/Models/README.md` instead of repeating that policy in this broad application overview.

## Related References

- [`references/app/README.md`](README.md)
- [`references/tests/README.md`](../tests/README.md)
- [`references/tests/TestSupport/README.md`](../tests/TestSupport/README.md)
- [`references/tests/Support/Models/README.md`](../tests/Support/Models/README.md)
