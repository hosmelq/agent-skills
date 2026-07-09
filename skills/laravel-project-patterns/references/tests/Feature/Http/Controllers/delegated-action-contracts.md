# Delegated Action Controller Contracts

## When To Use

Use this leaf when a controller maps validated HTTP input into a mocked action,
maps an action exception back to validation, or when deciding whether two
controller success cases are true duplicates.

## Pattern

### Focused References

- [Mapped Action Domain Rejection](delegated-action-contracts/mapped-domain-rejection.md): Use this leaf for exception-to-validation mapping through a mocked action.
- [Delegated Mutation Boundaries](delegated-action-contracts/mutation-boundaries.md): Use this leaf for mock inputs, return values, deduplication, and surviving HTTP contracts.

## Related References

- [`README.md`](README.md)
- [`actions/README.md`](actions/README.md)
- [`references/tests/Integration/Actions/README.md`](../../../Integration/Actions/README.md)
- [`references/core/http-and-request-boundaries.md`](../../../../core/http-and-request-boundaries.md)
