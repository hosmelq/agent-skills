# System Store Patterns

## Purpose

Route delegated store coverage to one focused scenario family.

## When To Use

Use this subrouter to select one delegated store or validation scenario family.

## Required Pattern

- [Mapping And Successful Paths](system-patterns/mapping-and-success.md)
- [Lifecycle And Managed Input](system-patterns/lifecycle-and-managed-input.md)
- [Scoped Uniqueness](system-patterns/scoped-uniqueness.md)
- [Range Creation](system-patterns/range.md)

## Coverage Expectations

Select every family affected by the live request, delegated action, or mapped
exception; do not load unrelated store scenarios.

## Do Not

- Do not use action integration coverage to delete a distinct HTTP mapping.

## Related References

- [Parent router](../store.md)
