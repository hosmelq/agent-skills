# tests/TestSupport

## Purpose

This reference defines conventions for test-only support utilities under `tests/TestSupport`.

## When To Use

Use this reference when your task matches this path and you need to follow its local conventions.

## Required Pattern

Use `tests/TestSupport` for test-only utilities and fixtures that support feature or integration tests but are not themselves application code.

### Current Role

This path currently supports external identity tests with deterministic signing keys and JWT helpers. Treat that as a pattern for external-identity support, not as a reason to create class-specific reference files.

### Usage Pattern

API feature tests should combine these helpers with HTTP fakes or SDK mocks. The test should own the external-identity scenario and expected assertion; the support helper should only produce deterministic external-identity-shaped input.

### Do Not

- Do not put application business logic in `tests/TestSupport`.
- Do not create one reference file per helper class unless the directory grows enough to justify it.

### Focused References

- [Deterministic Identity JWT Helper](identity-jwt-helper.md): Use this leaf for test-only external identity input consumed by HTTP fakes or SDK mocks.

## Coverage Expectations

Read the support helper and the feature tests consuming the same external
boundary. Test the behavior enabled by the helper, not the helper internals.

## Do Not

- Do not contradict the skill non-negotiables or project conventions.

## Related References

- [`references/tests/TestCase.md`](../TestCase.md)
- [`references/tests/Feature/Http/Controllers/Api/README.md`](../Feature/Http/Controllers/Api/README.md)
