# Data Input Actions

## Purpose

Route a Data-input action test to only the input contract in scope while
preserving the complete original example union in focused leaves.

## When To Use

Read this router when an action integration test passes a Spatie Laravel Data
input rather than a raw array.

## Required Pattern

- [`data-input-actions/update-persistence-and-identity.md`](data-input-actions/update-persistence-and-identity.md): Update Persistence And Identity.
- [`data-input-actions/create-required-and-defaults.md`](data-input-actions/create-required-and-defaults.md): Create Required Fields And Defaults.
- [`data-input-actions/public-id-resolution.md`](data-input-actions/public-id-resolution.md): Public ID Resolution.
- [`data-input-actions/range-update-optional-semantics.md`](data-input-actions/range-update-optional-semantics.md): Range Update Optional Semantics.

## Coverage Expectations

Select the leaves owned by the live input: returned identity, ordinary
persistence, omitted defaults, public-ID conversion, partial updates, or
explicit nullable clearing. Preserve each applicable case independently.

## Do Not

- Do not add an omission/default case unless the action owns that behavior.
- Do not duplicate ordinary persistence with an equivalent `expect()`.

## Related References

- [`../README.md`](../README.md)
