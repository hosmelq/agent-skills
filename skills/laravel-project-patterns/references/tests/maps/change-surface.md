# Test Change Surface

## When To Use

Use this leaf to map an application change to every affected test owner.

## Pattern

### Update Rule

When changing one application surface, update every affected test path:

- model cast/default/trait -> `tests/Unit/Models`
- persisted relationships, observers, route key persistence -> `tests/Integration/Models`
- resource contract -> `tests/Integration/Http/Resources`
- web route behavior -> `tests/Feature/Http/Controllers`
- API route behavior -> `tests/Feature/Http/Controllers/Api`
- middleware sharing/gating -> `tests/Feature/Http/Middleware`
- command behavior -> `tests/Feature/Console`
- support helper behavior -> the matching integration path for the touched support area

The canonical boundary for `tests/Integration/Models/**` is in `references/tests/Integration/Models/README.md`; use this file to choose the suite, then load the path reference before writing model tests.

Controller feature tests are not optional when a route, form request, policy, resource payload, redirect, toast, or binding chain changes. Resource integration tests are not a substitute for controller tests, and controller tests are not a substitute for exact resource contract tests.

When a controller list action scopes records, include exclusion coverage for records outside the route `Workspace` and for records under a different direct parent in the same `Workspace` when the route is nested. Use exact list counts plus `whereNot(...)` on the excluded public id.

## Related References

- [Parent router](../README.md)
