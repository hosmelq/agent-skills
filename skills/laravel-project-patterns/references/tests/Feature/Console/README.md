# tests/Feature/Console

## Purpose

This reference defines conventions for feature tests under `tests/Feature/Console`.

## When To Use

Use this reference when your task matches this path and you need to follow its local conventions.

## Required Pattern

Use `tests/Feature/Console/<Command>Test.php` for Artisan commands that interact with framework services, files, HTTP, config, or storage.

### Focused References

- [Console Command Test Shape](patterns/command-shape.md): Use this leaf for ordinary console command feature-test structure.
- [Download And Extract Command Tests](patterns/download-and-extract.md): Use this leaf for HTTP download, archive extraction, and filesystem command tests.

## Coverage Expectations

Read the command source and tests for commands with the same external boundary.
Cover only behavior owned by that command.

## Do Not

- Do not make live HTTP calls.
- Do not rely on developer-local database or file state outside configured test
  paths.
- Do not start long-running services from a feature command test.
- Do not contradict the skill non-negotiables or project conventions.

## Related References

- [`references/app/Console/Commands/README.md`](../../../app/Console/Commands/README.md)
- [`references/project/README.md`](../../../project/README.md)
