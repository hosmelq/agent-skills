# Tests Path Map

## Purpose

This reference defines conventions for the root `tests/` path map.

## When To Use

Use this reference when your task matches this path and you need to follow its local conventions.

## Required Pattern

Mirror the repository's `tests/` paths when choosing or creating a reference. Do not collapse these into one broad testing note when a path-specific reference exists.

### Focused References

- [Current Test Paths](maps/current-paths.md): Use this leaf to map a test path to its focused reference.
- [Test Suite Routing](maps/suite-routing.md): Use this leaf to choose the behavior-owning test suite.
- [Parallel Test Structure](maps/parallel-test-structure.md): Use this leaf when equivalent sibling scenarios should share naming and code structure.
- [Persistence Assertions](maps/persistence-assertions.md): Use this leaf to choose database assertions or reloaded Eloquent expectations.
- [Test Change Surface](maps/change-surface.md): Use this leaf to map an application change to every affected test owner.

## Coverage Expectations

Read the live test and an equivalent test with the same behavior owner,
precondition, operation, and outcome before changing its name, fixtures, or
assertions.

## Do Not

- Do not contradict the skill non-negotiables or project conventions.

## Related References

- [`references/tests/Feature/Http/Controllers/README.md`](Feature/Http/Controllers/README.md)
- [`references/tests/Feature/Http/Controllers/Api/README.md`](Feature/Http/Controllers/Api/README.md)
- [`references/tests/Feature/Console/README.md`](Feature/Console/README.md)
- [`references/tests/Feature/Http/Middleware/README.md`](Feature/Http/Middleware/README.md)
- [`references/tests/Integration/Actions/README.md`](Integration/Actions/README.md)
- [`references/tests/Integration/Http/Resources/README.md`](Integration/Http/Resources/README.md)
- [`references/tests/Integration/Models/README.md`](Integration/Models/README.md)
- [`references/tests/Unit/Enums/README.md`](Unit/Enums/README.md)
- [`references/tests/Unit/Models/README.md`](Unit/Models/README.md)
