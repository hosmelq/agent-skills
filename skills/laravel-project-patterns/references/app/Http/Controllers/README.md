# app/Http/Controllers

## Purpose

Route HTTP controller work to the smallest applicable entrypoint leaves.

## When To Use

Use this reference when creating or changing a controller, controller route contract, controller-owned redirect/toast, controller action delegation, or controller feature test boundary.

## Required Pattern

Use `app/Http/Controllers` for HTTP entrypoints. Keep controllers responsible for HTTP concerns: middleware, route-model binding, request validation, action input construction, action invocation, response shape, redirect, toast, and validation-error mapping.

### Reference Map

- [`patterns/web-controller-shape.md`](patterns/web-controller-shape.md): Web Controller Shape.
- [`patterns/delegated-actions.md`](patterns/delegated-actions.md): Delegated Actions.
- [`patterns/move-within-group.md`](patterns/move-within-group.md): Route-bound target movement with a nullable move-after record.
- [`patterns/lifecycle-controllers.md`](patterns/lifecycle-controllers.md): Lifecycle Controllers.
- [`patterns/api-controllers.md`](patterns/api-controllers.md): API Controllers.
- [`patterns/tests.md`](patterns/tests.md): Tests.

## Coverage Expectations

Read the live controller, request, route, policy, and equivalent controllers
with the same precondition, operation, and response. Preserve every applicable
HTTP contract even when a delegated action has integration coverage.

## Do Not

- Do not contradict the skill non-negotiables or project conventions.
- Do not replace controller feature tests with action integration tests when the HTTP route contract is what changed.
- Do not remove the only feature case for a distinct route, input shape,
  validation mapping, redirect, toast, or user-visible failure because its
  domain outcome resembles an action test.

## Related References

- [`lifecycle-resources.md`](lifecycle-resources.md)
- [`api-authentication.md`](api-authentication.md)
- [`references/app/Actions/README.md`](../../Actions/README.md)
- [`references/app/Http/Requests/README.md`](../Requests/README.md)
- [`references/tests/Feature/Http/Controllers/README.md`](../../../tests/Feature/Http/Controllers/README.md)
