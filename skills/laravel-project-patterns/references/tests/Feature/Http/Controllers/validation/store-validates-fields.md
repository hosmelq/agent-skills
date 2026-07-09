# Store Validation Dataset Snippets

## Purpose

This reference is a catalog of store validation dataset snippets for controller feature tests.

## When To Use

Use this file only after loading focused validation references that match the actual request rules.

## Required Pattern

This file is a catalog. Action templates already include a baseline `validates fields` test. Merge only the extra rules the store request actually uses.

Conventions:

- Keep each dataset `data` minimal.
- Assert exact message strings for every failing field.
- Order dataset cases alphabetically by dataset key unless an equivalent live
  sibling with the same request rules has a clearer field-specific order.
- Paired fields include both `required_with` directions and each range boundary.
- UI-only or server-managed inputs use `missing` or `prohibited` cases only when the Form Request explicitly rejects submitted values.
- Validation tests use an actor authorized for the route `Workspace` so validation runs after authorization.

Load focused files first:

- [`dataset-catalog.md`](dataset-catalog.md)
- [`required-with-and-array.md`](required-with-and-array.md)
- [`scoped-exists-and-unique.md`](scoped-exists-and-unique.md)
- [`prepare-for-validation.md`](prepare-for-validation.md)
- [`api-login-validation.md`](api-login-validation.md)

[Composite Store Dataset](store-validates-fields/base-dataset.md): load only
when assembling several generic rules. For one rule, use the focused dataset
catalog above.

For persisted-row uniqueness branches, use the ordered
[scoped validation catalog](scoped-exists-and-unique.md).

[Store General Error-Bag Validation](store-validates-fields/general-error-bag.md)

## Coverage Expectations

For store datasets, include server-managed missing fields, parent-dependent scoped exists, soft-deleted related-record rejection, paired `required_with` directions, conditional required rules, decimal precision, numeric bounds, string maximums, enum cases, range boundaries, and named general-error-bag payload failures when the request uses those rules.

Put persisted-row or domain failures in named tests. Put action-owned transactional guards in action integration tests and keep controller coverage for mocked exception-to-validation mapping.

## Do Not

- Do not contradict the skill non-negotiables or project conventions.
- Do not drop applicable validation coverage when adapting examples.
- Do not use real module, route, or entity names in examples.

## Related References

- [`references/tests/Feature/Http/Controllers/README.md`](../README.md)
- [`references/tests/Feature/Http/Controllers/validation/dataset-catalog.md`](dataset-catalog.md)
