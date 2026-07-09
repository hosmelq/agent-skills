# Complete Range And Open-Ended Update Example

## Purpose

Route this scenario family to only the focused cases in scope while
preserving the complete original ordered union in the leaves below.

## When To Use

Load only when implementing the complete four-contract test skeleton.

## Required Pattern

- [`complete-example/request-error-precedence.md`](complete-example/request-error-precedence.md): Request Error Precedence.
- [`complete-example/self-exclusion.md`](complete-example/self-exclusion.md): Self Exclusion.
- [`complete-example/nullable-and-open-ended-updates.md`](complete-example/nullable-and-open-ended-updates.md): Nullable And Open-Ended Updates.

## Coverage Expectations

Select the leaves owned by the live behavior. Preserve each distinct
precondition, failure, success, scope, and persistence contract.

## Do Not

- Do not load unrelated scenario families.
- Do not remove a case only because another layer has adjacent coverage.

## Related References

- [Parent router](../range-open-ended.md)
