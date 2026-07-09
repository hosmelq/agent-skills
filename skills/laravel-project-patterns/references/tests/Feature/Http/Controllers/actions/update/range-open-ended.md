# Delegated Range and Open-Ended Updates

## When To Use

Read this reference when an update route accepts half-open range or nullable
maximum input before delegating the mutation.

## Pattern

The Form Request owns request-safe range comparison. Mock the update action for
accepted input paths; Integration/Actions owns persistence, overlap guards,
nullable clearing, and any action-owned dependent state.

Select one contract:

- [Base-Validation Short Circuit](range-open-ended/base-validation-short-circuit.md)
- [Self-Overlap Exclusion](range-open-ended/self-overlap-exclusion.md)
- [Clear Maximum](range-open-ended/clear-maximum.md)
- [Stored Open-Ended Maximum](range-open-ended/stored-open-ended-maximum.md)
- [Complete Combined Example](range-open-ended/complete-example.md): load only
  when the full implementation skeleton is needed.

## Related References

- [`../update.md`](../update.md)
