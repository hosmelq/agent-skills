# tests/Feature/Http/Middleware

## Purpose

This reference defines conventions for middleware feature tests under `tests/Feature/Http/Middleware`.

## When To Use

Use this reference when your task matches this path and you need to follow its local conventions.

## Required Pattern

Use `tests/Feature/Http/Middleware/<Middleware>Test.php` for middleware behavior through real routes.

### Focused References

- [Access-Control Middleware Tests](patterns/access-control.md): Use this leaf for access-control middleware feature tests.
- [Inertia Shared Props Middleware Tests](patterns/inertia-shared-props.md): Use this leaf for request-scoped Inertia shared-prop tests.

## Coverage Expectations

Read the live middleware, bootstrap registration, and tests for the same gate
or shared-prop contract. Cover each owned request branch once.

## Do Not

- Do not call middleware methods directly when route behavior is the contract.
- Do not reuse global application routes when a local test route proves the
  middleware cleanly.
- Do not contradict the skill non-negotiables or project conventions.

## Related References

- [`references/app/Http/Middleware/README.md`](../../../../app/Http/Middleware/README.md)
- [`references/tests/Pest.md`](../../../Pest.md)
