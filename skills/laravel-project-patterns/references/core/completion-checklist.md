# Completion Checklist

## When To Use

Use this leaf after implementation and focused tests, before reporting a
Laravel project change complete.

## Pattern

- New migration matches the local style and avoids unsupported rollback/FK patterns.
- New or changed model has typed relationships, casts, docblock properties, and the expected route-key/public-id behavior.
- Factory can create a valid row with realistic defaults and coherent relationship ownership.
- Project tooling changes preserve the documented build graph, generated-code ordering, staged hooks, and local process definitions.
- `resources/js` changes preserve typed Inertia/Wayfinder contracts, accessible server-error mapping, dependent reload/reset behavior, and pending-state cleanup.
- `resources/views` shell changes preserve Inertia head/app slots, Vite entrypoints, font directives, locale/html metadata, and production/authenticated third-party scripts.
- `resources/react-email` changes keep source templates under `resources/react-email/mail`, use Nub commands, and treat exported Blade views/assets as generated output.
- Tests are placed in the correct suite and cover every touched surface: unit-level configuration/pure logic, integration-level persisted behavior/resources/support, feature-level HTTP/console/middleware behavior, and browser coverage when real browser UX is touched.
- Related model/resource/controller tests are updated when system behavior or serialized contracts change; do not add paired model relationship tests just to prove Laravel relationship wiring.
- Equivalent test scenarios use the same complete domain nouns, grammatical name template, fixture/action/assertion order, and assertion style as equivalent live siblings with the same precondition, operation, ownership boundary, and outcome.
- Database assertions prove ordinary persisted state once. `expect()` is reserved for separate identity, collection, or Eloquent behavior contracts, and no test introduces an avoidable `refresh()` by preloading a relation before the action.
- Controller coverage was checked against live nested siblings, not only action templates. If a nested child stores redundant `Workspace`/ancestor ownership, the controller tests include a same-parent mismatched-ownership `404` case and list actions exclude those records.
- Run the smallest relevant tests, for example:

```bash
php artisan test --compact tests/Unit/Models/<Model>Test.php tests/Integration/Models/<Model>Test.php
php artisan test --compact tests/Integration/Http/Resources/<Resource>Test.php
php artisan test --compact tests/Feature/Http/Controllers/<Controller>Test.php
```

- If PHP files changed, run:

```bash
vendor/bin/pint --dirty --format agent
```

## Related References

- [`references/tests/README.md`](../tests/README.md)
- [`references/MAP.md`](../MAP.md)
