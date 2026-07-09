# Conditional Input Normalization

## When To Use

Use when a partial update changes one field that determines how an omitted
dependent field must be mapped into the action input.

## Pattern

When a controlling option changes and no new dependent option is submitted,
map the dependent value to explicit `null`:

[Parent Option Change Normalization](conditional-input-normalization/parent-option-change.md)

For a mode-dependent numeric value, changing to the disabled mode clears it,
while an unrelated partial update preserves the stored value:

[Mode-Disabled Value Normalization](conditional-input-normalization/mode-disabled-value.md)

These cases test request-to-input mapping. Persistence of explicit `null`,
omission, and stored fallback remains in the action integration suite.

## Related References

- [`../pattern-catalog.md`](../pattern-catalog.md)
- [`../actions/update/stored-bound-validation.md`](../actions/update/stored-bound-validation.md)
- [`references/app/Http/Requests/patterns/normalization.md`](../../../../../app/Http/Requests/patterns/normalization.md)
