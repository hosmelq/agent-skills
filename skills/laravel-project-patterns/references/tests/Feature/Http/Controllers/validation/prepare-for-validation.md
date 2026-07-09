# `prepareForValidation()` Map

## Purpose

Route request-normalization and stored-value validation work to a small focused
reference.

## When To Use

Use this router when a Form Request mutates incoming data before rule
evaluation, validates submitted public IDs, or compares submitted values with
stored model values.

## Required Pattern

Do not use `prepareForValidation()` by default. First check whether validation
can run against the submitted public contract directly.

### Reference Map

- [`prepare-for-validation/public-id-resolution.md`](prepare-for-validation/public-id-resolution.md):
  public-ID validation, delegated input mapping, and action-owned resolution.
- [`prepare-for-validation/stored-and-normalized-values.md`](prepare-for-validation/stored-and-normalized-values.md):
  blank-field normalization, stored-bound comparisons, and normalized
  uniqueness.

## Coverage Expectations

Cover validation failures for malformed or out-of-scope input and every
distinct successful normalized request-to-input path. The action integration
test proves durable persistence and public-ID resolution.

## Do Not

- Do not submit internal integer IDs from controller tests when the public
  contract exposes public IDs.
- Do not use action integration coverage to remove the successful controller
  case that proves normalized input reaches the action.

## Related References

- [`README.md`](../README.md)
