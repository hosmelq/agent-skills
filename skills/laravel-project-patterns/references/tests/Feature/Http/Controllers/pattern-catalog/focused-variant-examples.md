# Focused Variant Examples

## When To Use

Read this focused reference when the task involves focused variant examples.

## Pattern

### Focused Variant Examples

An invokable nested default mutation keeps the same entry-point coverage even
though it does not fit a CRUD `describe(...)` block. When it delegates, mock
the action and do not create a previous-default fixture that the mock cannot
inspect. The action integration test owns exclusive-state persistence.

[Invokable Default Selection](focused-variants/invokable-default.md)

The action integration test proves that the former default is cleared, the
target becomes the only default, and other owners remain unchanged. Delegated
destroy controllers likewise keep only model identity, redirect, toast, and
mapped error coverage here; the cascade example is in
`tests/Integration/Actions/patterns/model-targeted-mutations.md`.

Lifecycle controllers that delegate to actions keep dependency failures at the HTTP boundary and leave the real guard to `tests/Integration/Actions`:

[Deactivation Rejection](focused-variants/deactivation-rejection.md)

Dependent option pages assert both the full page contract and the focused partial reload:

[Dependent Options Reload](focused-variants/dependent-options-reload.md)

When edit or show pages allow the stored related record to remain visible after it becomes unavailable for new records, assert that exception explicitly. Cover the unavailable states that the controller intentionally preserves, such as inactive, deactivated, or soft-deleted related records.

[Unavailable Related Record On Edit](focused-variants/unavailable-related-record-edit.md)

[Soft-Deleted Related Record On Show](focused-variants/soft-deleted-related-record-show.md)

## Related References

- [`../pattern-catalog.md`](../pattern-catalog.md)
