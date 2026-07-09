# HTTP Test Style

## When To Use

Use while writing or reviewing HTTP feature-test syntax and ordering.

## Pattern

- Mockery argument callbacks return booleans and check only contract-relevant
  arguments. Never put Pest expectations inside them.
- Assign every request-helper result to `$response`, then assert separately.
  Do not chain assertions directly from HTTP helpers.
- Follow the controller action-first, failure-to-success matrix. Nested routes
  cover every scoped binding boundary, including redundant denormalized
  ownership mismatches.
- Name rejected-behavior tests with observable verbs such as `rejects` or
  `prevents`, not after an internal exception-mapping mechanism.

## Related References

- [Parent router](../http-and-request-boundaries.md)
- [`references/tests/Feature/Http/Controllers/README.md`](../../tests/Feature/Http/Controllers/README.md)
