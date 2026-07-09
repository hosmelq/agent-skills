# Application Form Requests

## When To Use

Use this leaf when a change crosses Form Request concerns before selecting the focused request router.

## Pattern

### Form Requests

- Form requests define typed `rules(): array` methods with precise PHPDoc array shapes.
- Use `Rule::enum(...)`, `Rule::unique(...)->where(...)->withoutTrashed()`, and `ignore($model)` for scoped uniqueness.
- Use route parameters through local conventions: route-parameter attributes for typed route models when validation scopes to a bound parent, and helper methods when the request needs repeated access to a bound model.
- Use `prepareForValidation()` for derived input, PATCH-style partial updates, and relationship inference. Do not use it just to coerce a public ID into an integer foreign key when the controller can resolve the validated public ID before persistence.
- Do not add Form Request `input()` or `payload()` helpers when a Data input can be built from `$request->validated()` in the controller.
- Use `after(): array` for request-owned cross-field or domain validation that needs the hydrated model graph. If the guard needs action-owned transactional state, locks, or dependent-record checks, keep it in the action and map the action exception at the controller boundary when that is the sibling pattern.
- Store requests usually use `required`; update requests often use `sometimes|required`.
- Feature tests should assert exact validation messages when sibling tests do, especially custom domain messages.

## Related References

- [Parent router](../http-layer.md)
