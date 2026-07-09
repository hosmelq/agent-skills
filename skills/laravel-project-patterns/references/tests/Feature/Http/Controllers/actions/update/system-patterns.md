# System Update Patterns

## Purpose

Route delegated update coverage to one focused scenario family.

## When To Use

Use this subrouter to select one delegated update or validation scenario family.

## Required Pattern

- [Mapping And Successful Paths](system-patterns/mapping-and-success.md)
- [Lifecycle And Action Guards](system-patterns/lifecycle-and-action-guards.md)
- [Scoped And Stored Validation](system-patterns/scoped-and-stored-validation.md)
- [Range Updates](system-patterns/range.md)

## Coverage Expectations

Select every family affected by the live request, delegated action, or mapped
exception; do not load unrelated update scenarios.

## Do Not

- Do not use action integration coverage to delete a distinct HTTP mapping.

## Related References

- [Parent router](../update.md)
