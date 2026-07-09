# Form Request Ownership

## When To Use

Use for validation, normalization, public IDs, or request/action guard ownership.

## Pattern

- Store and update validation uses public IDs when exposed by the form. Convert
  them after validation only when persistence requires it; do not use
  `prepareForValidation()` solely to fit an integer foreign key.
- Inspect the application Form Request bootstrap before handling unknown or
  server-managed fields. With `FormRequest::failOnUnknownFields()`, omit
  non-contract fields from `rules()` instead of silently `exclude`-ing them.
- Form Requests own web input validation, normalization, and request-safe
  cross-field rules. Actions own guards requiring transactional state or
  dependent-record checks; map their domain exception at the controller when
  that is the sibling pattern.
- Avoid controller-created validation exceptions unless a live sibling owns the
  same boundary. API session controllers may own external-token or
  session-domain validation failures.

## Related References

- [Parent router](../http-and-request-boundaries.md)
- [`references/app/Http/Requests/README.md`](../../app/Http/Requests/README.md)
