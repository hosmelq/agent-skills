# tests/Pest.php

## Purpose

This reference defines conventions for `tests/Pest.php`.

## When To Use

Use this reference when your task matches this path and you need to follow its local conventions.

## Required Pattern

`tests/Pest.php` defines global behavior and helpers for the Unit, Integration, and Feature suites. Read it before writing any test that uses authentication, toasts, frozen time, Vite behavior, lock-query assertions, or shared helper functions.

### Focused References

- [Pest Global Setup](Pest/global-setup.md): Use this leaf for global suite setup and shared helper registration.
- [Pest Expectation Chains](Pest/expectation-chains.md): Use this leaf for project expectation-chain and subject-switching style.
- [Shared Login Helper](Pest/login.md): Use this leaf for authenticated test setup through the shared login helper.
- [Lock Query Assertions](Pest/lock-query-assertions.md): Use this leaf only when an existing justified database lock must be asserted.
- [Toast Assertions](Pest/toast-assertions.md): Use this leaf for redirect toast assertions.

## Coverage Expectations

Use these helpers instead of per-file helper duplication. If a new helper is needed, first prove the pattern repeats across multiple current test files.

## Do Not

- Do not contradict the skill non-negotiables or project conventions.

## Related References

- [`references/tests/README.md`](README.md)
