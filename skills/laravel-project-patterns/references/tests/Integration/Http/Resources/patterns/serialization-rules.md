# Resource Serialization Rules

## When To Use

Use this leaf for field-level serialization contracts.

## Pattern

### Serialization Rules

- `id` is the public id, not the internal database id.
- Do not expose raw relationship integer IDs unless the actual resource explicitly includes them. Prefer nested resources or public IDs when that is the project contract.
- Dates use `toJSON()`.
- Decimal casts serialize as strings with the configured scale.
- Enums serialize as backing values.
- For model accessors and casts passed through unchanged, use model-backed expectations when model tests already prove their exact behavior; do not duplicate that owner contract here.
- Resource-owned formatting of phone numbers, value objects, or derived fields needs deterministic inputs and literal final output; never repeat the transformation in the expectation.
- Nested resources should be asserted as full nested arrays when included.
- Conditional fields need both branches when the resource has conditional branches. If the resource returns Laravel's missing value, assert the key is absent. If the resource passes an explicit `null` default, assert the key is present with `null`. That branch may assert only the conditional behavior when another test already covers the base serialized contract.
- Address-style payloads are nested contract arrays. Assert region/subdivision display names, region/subdivision codes, coordinates, normalized phone number output, and null fields exactly.
- `deactivated_at` is asserted as the serialized value the resource returns: use `null` when null, and `toJSON()` only when the resource emits a timestamp instance/string.

## Related References

- [Parent router](../README.md)
