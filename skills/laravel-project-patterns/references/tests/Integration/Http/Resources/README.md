# tests/Integration/Http/Resources

## Purpose

This reference defines conventions for exact resource contract tests under `tests/Integration/Http/Resources`.

## When To Use

Use this reference when your task matches this path and you need to follow its local conventions.

## Required Pattern

Use `tests/Integration/Http/Resources/<Resource>Test.php` for exact JSON resource contracts.

### Focused References

- [Exact Resource Contract Shape](patterns/exact-contract-shape.md): Use this leaf for full-array resource assertion setup and style.
- [Resource Serialization Rules](patterns/serialization-rules.md): Use this leaf for field-level serialization contracts.
- [Resource Fixtures And Update Triggers](patterns/fixtures-and-update-triggers.md): Use this leaf for resource fixture design and update triggers.

## Coverage Expectations

Every resource class should have an exact contract test when it serializes a project-facing model. Update resource tests whenever controller props rely on the resource output, even if the controller test only checks selected paths.
If a controller page uses a resource for list/detail props, the controller test may assert only the public id and page context, but the resource test must still assert the complete serialization format. Add a separate `array_keys(...)` assertion when field order is intentionally part of the contract.

## Do Not

- Do not contradict the skill non-negotiables or project conventions.

## Related References

- [`references/app/Http/Resources/README.md`](../../../../app/Http/Resources/README.md)
