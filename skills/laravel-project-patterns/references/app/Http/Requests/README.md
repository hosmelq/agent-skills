# app/Http/Requests

## Purpose

This reference defines project conventions for controller validation and request normalization under `app/Http/Requests`.

## When To Use

Use this reference when creating or changing a Form Request, request-owned validation rule, request normalization path, API request, or controller feature test for validation.

## Required Pattern

Use `app/Http/Requests` for controller validation, request normalization, route-bound validation scope, request-owned cross-field validation, and API input contracts.

### Reference Map

- [`patterns/request-shape.md`](patterns/request-shape.md): Request Shape.
- [`patterns/normalization.md`](patterns/normalization.md): Normalization.
- [`patterns/move-within-group.md`](patterns/move-within-group.md): Move-after public-ID validation for a route-bound target.
- [`patterns/cross-field-and-domain-validation.md`](patterns/cross-field-and-domain-validation.md): Cross-Field And Domain Validation.
- [`patterns/server-managed-and-empty-requests.md`](patterns/server-managed-and-empty-requests.md): Server-Managed And Empty Requests.
- [`patterns/api-requests.md`](patterns/api-requests.md): API Requests.
- [`patterns/tests.md`](patterns/tests.md): Tests.

## Coverage Expectations

Read the live request, consuming controller, route, and requests with the same
store/update/move/API mode and field contract. Cover request-owned behavior
through the HTTP suite; cover action-owned transactional behavior separately.

## Do Not

- Do not contradict the skill non-negotiables or project conventions.
- Do not move Data input construction into Form Request helpers when the controller boundary already owns it.
- Do not silently drop unknown or server-managed input.

## Related References

- [`references/app/Http/Controllers/README.md`](../Controllers/README.md)
- [`references/app/Actions/README.md`](../../Actions/README.md)
- [`references/tests/Feature/Http/Controllers/README.md`](../../../tests/Feature/Http/Controllers/README.md)
