# Project Files And Tooling

## Purpose

Route project files outside the main application, database, resources, and test
suites to the smallest applicable reference.

## When To Use

Use this reference when changing:

- `routes/*.php`
- `config/*.php`
- `lang/**/*.php`
- `bootstrap/*.php`
- `public/*.php`
- `database/seeders/*.php`
- root configuration and developer tooling such as environment examples,
  Composer/Nub scripts, TypeScript, formatters, hooks, Solo, Rector, PHPStan,
  Pint, and dependency analysis

## Required Pattern

Supporting PHP files still use strict types, explicit imports, typed closures where practical, and the smallest configuration surface that matches the live framework contract.

### Reference Map

- [`patterns/routes.md`](patterns/routes.md): Routes.
- [`patterns/bootstrap-and-public-entrypoints.md`](patterns/bootstrap-and-public-entrypoints.md): Bootstrap And Public Entrypoints.
- [`patterns/configuration-files.md`](patterns/configuration-files.md): Configuration Files.
- [`patterns/localization-files.md`](patterns/localization-files.md): Localization Files.
- [`patterns/seeders.md`](patterns/seeders.md): Seeders.
- [`patterns/tooling-php.md`](patterns/tooling-php.md): Tooling PHP.
- [`configuration-and-tooling.md`](configuration-and-tooling.md): environment,
  runtime, Composer/Nub, frontend build order, formatting, hooks, and local
  process orchestration.

## Coverage Expectations

Read the exact supporting PHP file and equivalent live application/test references before changing behavior. For route changes, update controller/API/controller-test references and tests. For config changes, test the behavior through the surface that consumes the config. For localization changes, update enum/unit tests, request/controller tests, or exception/action tests that assert the translated message.

## Do Not

- Do not put business logic in route, config, bootstrap, public entrypoint, or tooling files.
- Do not add real module/entity examples to this reference.
- Do not treat generated/cache PHP as source evidence.

## Related References

- [`configuration-and-tooling.md`](configuration-and-tooling.md)
- [`references/app/README.md`](../app/README.md)
- [`references/app/Providers/README.md`](../app/Providers/README.md)
- [`references/tests/README.md`](../tests/README.md)
- [`references/tests/Feature/Http/Controllers/README.md`](../tests/Feature/Http/Controllers/README.md)
