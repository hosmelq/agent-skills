# Range Update Guards

## Purpose

Route this scenario family to only the focused cases in scope while
preserving the complete original ordered union in the leaves below.

## When To Use

Read this focused reference when the task involves range update guards.

## Required Pattern

For range-style update actions, cover same-scope failures, current-record exclusion, nullable clearing, and stored open-ended values when the action owns the range comparison.

- [`range-update-guards/overlap-and-open-ended-failures.md`](range-update-guards/overlap-and-open-ended-failures.md): Overlap And Open-Ended Failures.
- [`range-update-guards/self-exclusion.md`](range-update-guards/self-exclusion.md): Self Exclusion.
- [`range-update-guards/nullable-and-open-ended-updates.md`](range-update-guards/nullable-and-open-ended-updates.md): Nullable And Open-Ended Updates.

## Coverage Expectations

Select the leaves owned by the live behavior. Preserve each distinct
precondition, failure, success, scope, and persistence contract.

## Do Not

- Do not load unrelated scenario families.
- Do not remove a case only because another layer has adjacent coverage.

## Related References

- [`../README.md`](../README.md)
