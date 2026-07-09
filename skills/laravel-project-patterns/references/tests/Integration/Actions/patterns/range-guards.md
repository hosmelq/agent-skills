# Range Guards

## Purpose

Route this scenario family to only the focused cases in scope while
preserving the complete original ordered union in the leaves below.

## When To Use

Read this focused reference when the task involves range guards.

## Required Pattern

For range-style create actions, cover same-scope failures and success variants:

- [`range-guards/overlap-and-open-ended-failures.md`](range-guards/overlap-and-open-ended-failures.md): Overlap And Open-Ended Failures.
- [`range-guards/adjacency-and-soft-delete.md`](range-guards/adjacency-and-soft-delete.md): Adjacency And Soft Delete.
- [`range-guards/scope-and-recreation.md`](range-guards/scope-and-recreation.md): Scope And Recreation.

## Coverage Expectations

Select the leaves owned by the live behavior. Preserve each distinct
precondition, failure, success, scope, and persistence contract.

## Do Not

- Do not load unrelated scenario families.
- Do not remove a case only because another layer has adjacent coverage.

## Related References

- [`../README.md`](../README.md)
