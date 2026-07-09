# Four-Resource Store Request and Range Validation

## Purpose

Route this scenario family to only the focused cases in scope while
preserving the complete original ordered union in the leaves below.

## When To Use

Read this reference when the task requires four-resource store request and range validation.

## Required Pattern

- [`request-and-range-validation/request-validation-boundary.md`](request-and-range-validation/request-validation-boundary.md): Request Validation Boundary.
- [`request-and-range-validation/action-range-exceptions.md`](request-and-range-validation/action-range-exceptions.md): Action Range Exceptions.
- [`request-and-range-validation/request-error-precedence.md`](request-and-range-validation/request-error-precedence.md): Request Error Precedence.

## Coverage Expectations

Select the leaves owned by the live behavior. Preserve each distinct
precondition, failure, success, scope, and persistence contract.

## Do Not

- Do not load unrelated scenario families.
- Do not remove a case only because another layer has adjacent coverage.

## Related References

- [`../four-resource.md`](../four-resource.md)
