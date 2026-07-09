# Enum Shape

## When To Use

Read this focused reference when the task involves enum shape.

## Pattern

### Enum Shape

- Use TitleCase case names.
- Use `ArchTech\Enums\Values` when callers need `values()`.
- Use `InvokableCases` when code calls `Enum::CaseName()`, and keep matching `@method` PHPDoc for the returned primitive: the backing value for backed enums, or the case name string for pure enums.
- Use `Comparable` on option-style enums that siblings compare directly.
- Use `Options` plus the project enum metadata attribute when the enum exposes translated `{ label, value }` options for UI/form contracts.
- Keep shared option helpers under enum concerns and metadata support under enum metadata properties.
- Keep enum helper methods deterministic and side-effect free.
- When an enum exposes labels, values, options, alphabets, variants, or units, treat that output as application contract.
- Keep configured alphabets explicit strings on the enum when actions pass them into NanoID generation.

## Related References

- [`../README.md`](../README.md)
