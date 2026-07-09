# API/JSON Mode for Controller Tests

## Purpose

This reference defines JSON/API adaptations for controller feature tests.

## When To Use

Use this reference when the controller test flow uses `getJson()` or
`postJson()`, JSON validation errors, token responses, public session
endpoints, or the protected current-actor resource.

## Required Pattern

Inspect the route, controller action, form request, resource, and response helpers before adapting any template. JSON assertions must match the real envelope and field names.

Canonical status rule:

- Policy denial -> `assertForbidden()` (`403`).
- Binding mismatch -> `assertNotFound()` (`404`).
- Guest protected endpoint -> `assertUnauthorized()` (`401`).
- Validation failure -> `assertUnprocessable()->assertJsonValidationErrors([...])`.

For validation, assert exact messages instead of keys only.

### Reference Map

- [`api-json/request-and-assertion-mapping.md`](api-json/request-and-assertion-mapping.md): Request and Assertion Mapping.
- [`api-json/protected-vs-public-endpoints.md`](api-json/protected-vs-public-endpoints.md): Protected vs Public Endpoints.
- [`api-json/public-endpoint-template.md`](api-json/public-endpoint-template.md): Public Endpoint Template.
- [`api-json/protected-endpoint-template.md`](api-json/protected-endpoint-template.md): Protected Endpoint Template.
- [`api-json/json-store-example.md`](api-json/json-store-example.md): Public one-time-code request, action mock, and notification.
- [`api-json/json-update-example.md`](api-json/json-update-example.md): Existing external identity with changed provider email.

## Coverage Expectations

Use the live controller, API route file, form request, resource, and equivalent
live siblings with the same transport and identity contract to decide the
complete JSON matrix. The current JSON surface consists of
public session-style endpoints plus one protected current-actor endpoint; do
not invent JSON CRUD routes from the web resource controllers.

## Do Not

- Do not contradict the skill non-negotiables or project conventions.
- Do not drop applicable JSON transport coverage when adapting examples.
- Do not use real module, route, or entity names in examples.

## Related References

- [`references/tests/Feature/Http/Controllers/README.md`](../README.md)
