# Test Map

## When To Use

Use this map to select one test suite or test-support router.

## Pattern

- [`tests/README.md`](../tests/README.md): suite selection and path-to-reference
  map.
  - [`tests/ArchitectureTest.md`](../tests/ArchitectureTest.md): architecture rules.
  - [`tests/Pest.md`](../tests/Pest.md): global Pest configuration and helpers.
  - [`tests/TestCase.md`](../tests/TestCase.md): base test behavior.
- Unit:
  - [`tests/Unit/Enums/README.md`](../tests/Unit/Enums/README.md): enum unit tests.
  - [`tests/Unit/Models/README.md`](../tests/Unit/Models/README.md): model unit
    tests.
- Integration:
  - [`tests/Integration/Actions/README.md`](../tests/Integration/Actions/README.md):
    action-test routing.
    - `tests/Integration/Actions/patterns/*.md`: focused action-test patterns.
    - [`tests/Integration/Actions/scenario-catalog.md`](../tests/Integration/Actions/scenario-catalog.md):
      scenario map.
    - `tests/Integration/Actions/scenario-catalog/*.md`: focused scenario
      families.
  - [`tests/Integration/Http/Resources/README.md`](../tests/Integration/Http/Resources/README.md):
    exact resource contracts.
  - [`tests/Integration/Listeners/README.md`](../tests/Integration/Listeners/README.md):
    listener integration tests.
  - [`tests/Integration/Models/README.md`](../tests/Integration/Models/README.md):
    persisted system/model behavior.
    - [`tests/Integration/Models/Concerns/README.md`](../tests/Integration/Models/Concerns/README.md):
      persisted concern behavior.
  - [`tests/Integration/Support/Media/README.md`](../tests/Integration/Support/Media/README.md):
    media support integration tests.
- Feature:
  - [`tests/Feature/Console/README.md`](../tests/Feature/Console/README.md): console
    command tests.
  - [`tests/Feature/Http/Middleware/README.md`](../tests/Feature/Http/Middleware/README.md):
    middleware tests.
  - [`tests/Feature/Models/Concerns/README.md`](../tests/Feature/Models/Concerns/README.md):
    route-binding behavior for model concerns.
  - [`tests/Feature/Http/Controllers/README.md`](../tests/Feature/Http/Controllers/README.md):
    central web-controller test router.
    - [`tests/Feature/Http/Controllers/entrypoint-contracts.md`](../tests/Feature/Http/Controllers/entrypoint-contracts.md):
      HTTP ownership and surviving-contract rules.
    - [`tests/Feature/Http/Controllers/delegated-action-contracts.md`](../tests/Feature/Http/Controllers/delegated-action-contracts.md):
      delegated mocks, mapped domain errors, and deduplication gate.
    - [`tests/Feature/Http/Controllers/Api/README.md`](../tests/Feature/Http/Controllers/Api/README.md):
      API controller tests.
    - [`tests/Feature/Http/Controllers/actions/README.md`](../tests/Feature/Http/Controllers/actions/README.md):
      choose `create`, `store`, `index`, `show`, `edit`, `update`, or `destroy`,
      then choose the nesting-depth leaf exposed by that action.
    - [`tests/Feature/Http/Controllers/route-patterns.md`](../tests/Feature/Http/Controllers/route-patterns.md):
      route-shape and binding map.
    - [`tests/Feature/Http/Controllers/pattern-catalog.md`](../tests/Feature/Http/Controllers/pattern-catalog.md):
      cross-cutting controller-test pattern map.
    - [`tests/Feature/Http/Controllers/modes/api-json.md`](../tests/Feature/Http/Controllers/modes/api-json.md):
      JSON transport map and focused mode leaves.
    - `tests/Feature/Http/Controllers/validation/*.md`: select only validation
      families present in the live Form Request.
    - [`tests/Feature/Http/Controllers/validation/dataset-catalog.md`](../tests/Feature/Http/Controllers/validation/dataset-catalog.md):
      select only the dataset families used by the request rules.
- Test infrastructure:
  - [`tests/Support/Models/README.md`](../tests/Support/Models/README.md): test-only
    support models.
  - [`tests/TestSupport/README.md`](../tests/TestSupport/README.md): shared test
    utilities.
  - [`tests/migrations/README.md`](../tests/migrations/README.md): test-only schema.
  - [`tests/testfiles/README.md`](../tests/testfiles/README.md): binary and text
    fixtures.

## Related References

- [Parent router](../MAP.md)
