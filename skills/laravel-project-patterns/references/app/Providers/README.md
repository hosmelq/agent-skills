# app/Providers

## Purpose

This reference defines conventions for providers under `app/Providers`.

## When To Use

Use this reference when your task matches this path and you need to follow its local conventions.

## Required Pattern

Use `app/Providers` for application-wide framework configuration, package integration, and container bindings.

### Reference Map

- [`patterns/provider-shape.md`](patterns/provider-shape.md): Provider Shape.
- [`patterns/provider-registration.md`](patterns/provider-registration.md): Provider Registration.
- [`patterns/application-provider-example.md`](patterns/application-provider-example.md): Application Provider Example.
- [`patterns/fortify-provider-example.md`](patterns/fortify-provider-example.md): Fortify Provider Example.
- [`patterns/deferrable-binding-provider-example.md`](patterns/deferrable-binding-provider-example.md): Deferrable Binding Provider Example.
- [`patterns/fluent-panel-provider-example.md`](patterns/fluent-panel-provider-example.md): Fluent Panel Provider Example.
- [`patterns/package-configuration-provider-example.md`](patterns/package-configuration-provider-example.md): Package Configuration Provider Example.
- [`patterns/tests.md`](patterns/tests.md): Tests.

## Coverage Expectations

Read the live provider and the exact framework/package contract it configures.
Cover the affected consumer unless registration or binding is itself public
application behavior.

## Do Not

- Do not contradict the skill non-negotiables or project conventions.

## Related References

- [`references/app/functions.php.md`](../functions.php.md)
- [`references/tests/Pest.md`](../../tests/Pest.md)
