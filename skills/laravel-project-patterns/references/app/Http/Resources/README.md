# app/Http/Resources

## Purpose

This reference defines project conventions for JSON serialization contracts under `app/Http/Resources`.

## When To Use

Use this reference when creating or changing an API/Inertia resource, serialized field contract, nested resource, conditional resource field, or resource integration test.

## Required Pattern

Use `app/Http/Resources` for JSON serialization contracts.

### Test Mapping

- Resource serialization contracts are covered through `tests/Integration/Http/Resources`.
- Assert the complete serialized array with `toEqual([...])` for value contract coverage.
- Do not use partial match assertions for the primary resource contract coverage.
- Conditional resources need both branches: exact nested array when present and either an omitted-key assertion or explicit `null` assertion according to the resource default. The absent/null branch can assert only that conditional behavior when another test already covers the complete base contract.

### Focused References

- [HTTP Resource Shape](patterns/resource-shape.md): base resource class and public serialization shape.
- [Derived And Conditional Fields](patterns/derived-and-conditional-fields.md): deterministic values, conditional resources, and nullable output.
- [Reference-Data Static Cache](patterns/reference-data-static-cache.md): immutable reference lookup caching and Octane safety.

## Coverage Expectations

Every resource field is part of the contract. When a resource changes, update the exact integration resource test and any controller tests that assert the same prop shape.

## Do Not

- Do not contradict the skill non-negotiables or project conventions.
- Do not expose internal integer IDs when the external contract uses public IDs.

## Related References

- [`references/tests/Integration/Http/Resources/README.md`](../../../tests/Integration/Http/Resources/README.md)
