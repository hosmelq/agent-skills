# app/Console/Commands

## Purpose

This reference defines conventions for Artisan commands under `app/Console/Commands`.

## When To Use

Use this reference when your task matches this path and you need to follow its local conventions.

## Required Pattern

Use `app/Console/Commands` for application infrastructure, scheduled or manually run maintenance, and data ingestion.

### Test Mapping

- Command behavior is covered through `tests/Feature/Console` when the command is the entrypoint being changed.
- Fake HTTP responses. Use real fixtures, temp paths, or storage assertions when the command contract is file extraction, downloads, or persisted filesystem side effects.
- Use Pest's `artisan('command:name')->assertSuccessful()`.
- Assert the actual file/database side effect the command owns.

### Focused References

- [Console Command Shape](patterns/command-shape.md): Use this leaf for the application console-command implementation contract.
- [Download And Extract Command](patterns/download-and-extract.md): safe HTTP download, validation, and atomic replacement.

## Coverage Expectations

Read the live command and equivalent commands with the same external boundary.
Cover only behavior owned by the command.

## Do Not

- Do not contradict the skill non-negotiables or project conventions.

## Related References

- [`references/tests/Feature/Console/README.md`](../../../tests/Feature/Console/README.md)
- [`references/project/README.md`](../../../project/README.md)
