# Application Patterns

## Purpose

Route application work to the smallest touched behavior surfaces.

## When To Use

Use for PHP application code under `app/**` before selecting its focused
router.

## Required Pattern

Use this reference after reading the exact sibling files for the surface being changed. These patterns are drawn from the full `app/` tree and should guide new code without freezing the application into one narrow module shape.

Start with [`php-shape.md`](php-shape.md) for the global PHP file contract,
then load only the matching domain router below. Use
[`http-layer.md`](http-layer.md) when a change crosses controllers, Form
Requests, or JSON resources. Use
[`test-support.md`](test-support.md) only when the change touches global test
setup, authentication helpers, external HTTP boundaries, or reusable
test-support infrastructure.

### Focused References

- [Models And Policies](maps/models-and-policies.md): Use this leaf for shared model and policy boundaries before selecting their focused routers.
- [Operational Application Components](maps/operational-components.md): Use this leaf for shared action, command, listener, notification, provider, and support boundaries.

## Coverage Expectations

Read the live file and equivalent siblings with the same precondition,
operation, and outcome. Cover every touched owner surface without adding
adjacent cases for symmetry.

## Do Not

- Do not contradict the skill non-negotiables or project conventions.

## Related References

- [`php-shape.md`](php-shape.md)
- [`http-layer.md`](http-layer.md)
- [`test-support.md`](test-support.md)
- [`references/app/Actions/README.md`](Actions/README.md)
- [`references/app/Http/Controllers/README.md`](Http/Controllers/README.md)
- [`references/app/Http/Requests/README.md`](Http/Requests/README.md)
- [`references/app/Models/README.md`](Models/README.md)
- [`references/app/Providers/README.md`](Providers/README.md)
- [`references/app/Support/README.md`](Support/README.md)
- [`references/tests/README.md`](../tests/README.md)
