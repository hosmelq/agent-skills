# tests/Feature/Http/Controllers/Api

## Purpose

This reference defines conventions for JSON API controller feature tests.

## When To Use

Use this reference for tests under `tests/Feature/Http/Controllers/Api/**`.

## Required Pattern

Load `../modes/api-json.md` for transport rules and keep the same entry-point discipline as web controller tests.

### Focused References

- [API Test And Validation Shape](patterns/file-and-validation-shape.md): Use this leaf for JSON controller test structure and validation datasets.
- [API Endpoint Selection And Order](patterns/endpoint-selection.md): Use this leaf to select public or protected endpoint coverage and its case order.
- [External Identity Endpoints](patterns/external-identity.md): Use this leaf for external identity verification and account-linking scenarios.
- [Access-Code Endpoints](patterns/access-code.md): Use this leaf for access-code request and login scenarios.

## Coverage Expectations

Use the live controller, routes, form requests, resources, and equivalent live
siblings with the same transport and identity contract to decide the complete
API matrix. Preserve examples, but keep them synthetic and only implement
routes that exist.

## Do Not

- Do not use web helpers for API endpoints.
- Do not assert only status when side effects are the contract.
- Do not use real external service, route, or entity names in examples.

## Related References

- [`references/tests/Feature/Http/Controllers/README.md`](../README.md)
