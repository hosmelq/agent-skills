# Update Validation Dataset Snippets

## Purpose

This reference is a catalog of update validation dataset snippets for controller feature tests.

## When To Use

Use this file only after loading focused validation references that match the actual update rules.

## Required Pattern

This file is a catalog. Action templates already include a baseline `validates fields` test. Merge only the extra rules the update request actually uses.

Conventions:

- Keep each dataset `data` minimal.
- Assert exact message strings for every failing field.
- Order dataset cases alphabetically by dataset key unless an equivalent live
  sibling with the same request rules has a clearer field-specific order.
- For `sometimes|required`, prefer one blank-value case covering every field that shares the rule.
- Paired fields include both `required_with` directions and each range boundary.
- UI-only or server-managed inputs use `missing` or `prohibited` cases only when the Form Request explicitly rejects submitted values.
- Use named tests outside the dataset for stored model values or related records.
- Validation tests use an actor authorized for the route `Workspace` so validation runs after authorization.

Load focused files first:

- [`dataset-catalog.md`](dataset-catalog.md)
- [`required-with-and-array.md`](required-with-and-array.md)
- [`scoped-exists-and-unique.md`](scoped-exists-and-unique.md)
- [`prepare-for-validation.md`](prepare-for-validation.md)

[Composite Update Dataset](update-validates-fields/base-dataset.md): load only
when assembling several generic rules. For one rule, use the focused dataset
catalog above.

### Reference Map

- [`update-validates-fields/scoped-unique-on-update.md`](update-validates-fields/scoped-unique-on-update.md): Scoped Unique on Update.
- [`update-validates-fields/stored-value-comparison.md`](update-validates-fields/stored-value-comparison.md): Stored-Value Comparison.
- [`update-validates-fields/payload-level-error-bag.md`](update-validates-fields/payload-level-error-bag.md): Payload-Level Error Bag.
- [`update-validates-fields/request-owned-dependent-record-prohibition.md`](update-validates-fields/request-owned-dependent-record-prohibition.md): Request-Owned Dependent Record Prohibition.
- [`error-precedence.md`](error-precedence.md): Prevent stored-state validation from adding a second error after a base rule already failed.

## Coverage Expectations

For update datasets, include server-managed missing fields, immutable-field prohibitions, scoped exists rules, soft-deleted related-record rejection, paired `required_with` directions, conditional required rules, decimal precision, numeric bounds, string maximums, enum cases, range boundaries, request-owned dependent-record prohibitions, stored-value comparisons, positive partial/open-ended paths, and named general-error-bag payload failures when those rules exist.

Put persisted-row or domain failures in named tests. Put action-owned transactional guards in action integration tests and keep controller coverage for mocked exception-to-validation mapping.

## Do Not

- Do not contradict the skill non-negotiables or project conventions.
- Do not drop applicable validation coverage when adapting examples.
- Do not use real module, route, or entity names in examples.

## Related References

- [`references/tests/Feature/Http/Controllers/README.md`](../README.md)
- [`references/tests/Feature/Http/Controllers/validation/dataset-catalog.md`](dataset-catalog.md)
