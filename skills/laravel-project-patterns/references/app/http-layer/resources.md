# Application HTTP Resources

## When To Use

Use this leaf when a change crosses resource concerns before selecting the focused resource router.

## Pattern

### Resources

- Resources extend `JsonResource`, add `@property Model $resource`, and implement `toArray(Request $request): array`.
- The serialized `id` is usually the model public id, not the internal integer key.
- Dates are serialized in resource tests with `toJSON()`.
- Decimal casts serialize as strings. Enum values serialize as backing values.
- Phone number/value-object output should match the resource method exactly, such as E164 or national formatting.
- Conditional fields should be tested both present and omitted when the resource uses conditional helpers.
- Static caches in resources are an existing local pattern for reference-data tables; do not expand them casually, and account for Octane if the data can change during a worker lifetime.

## Related References

- [Parent router](../http-layer.md)
