# app/Http/Middleware

## Purpose

This reference defines conventions for middleware under `app/Http/Middleware`.

## When To Use

Use this reference when your task matches this path and you need to follow its local conventions.

## Required Pattern

Use `app/Http/Middleware` for request gating, Inertia shared props, and request-level behavior.

### Test Mapping

- Middleware behavior is covered through `tests/Feature/Http/Middleware`.
- Define local test routes instead of relying on broad app routes.
- Assert Inertia shared props through `assertInertia`.
- Assert access middleware with guest, non-authorized, and authorized requests.

### Focused References

- [Access-Control Middleware](patterns/access-control.md): Use this leaf for access-control middleware shape and behavior.
- [Inertia Shared Props Middleware](patterns/inertia-shared-props.md): Use this leaf for request-scoped Inertia shared props.

## Coverage Expectations

Read the live middleware, bootstrap registration, and middleware with the same
request boundary. Cover the exact gate or shared-prop contract it owns.

## Do Not

- Do not contradict the skill non-negotiables or project conventions.

## Related References

- [`references/tests/Feature/Http/Middleware/README.md`](../../../tests/Feature/Http/Middleware/README.md)
- [`references/tests/Pest.md`](../../../tests/Pest.md)
