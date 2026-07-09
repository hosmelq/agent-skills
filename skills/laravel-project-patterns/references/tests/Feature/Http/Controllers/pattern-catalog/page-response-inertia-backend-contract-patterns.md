# Page Response / Inertia Backend Contract Patterns

## When To Use

Read this focused reference when the task involves page response / inertia backend contract patterns.

## Pattern

### Page Response / Inertia Backend Contract Patterns

- Assert the component string.
- Assert every ancestor public ID prop used by the page.
- Assert the shown or edited resource public ID.
- Assert enum, reference-data, and option props when the form needs them.
- Assert derived booleans that lock or alter form fields.
- Assert partial reload props with `reloadOnly(...)` when the controller supports dependent options.
- Assert index collections include in-scope records and omit out-of-scope records.
- Keep test names aligned to the order layer: `prevents`, `returns not found`, `validates`, `rejects`, `allows`, `clears`, `preserves`, `does not include`, or the primary success phrase.

## Related References

- [`../pattern-catalog.md`](../pattern-catalog.md)
