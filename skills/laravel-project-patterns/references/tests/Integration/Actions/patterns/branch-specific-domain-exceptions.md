# Branch-Specific Domain Exceptions

## When To Use

Read this focused reference when the task involves branch-specific domain exceptions.

## Pattern

### Branch-Specific Domain Exceptions

When the action throws a domain exception, assert the exception class and exact message with `toThrow(Class::class, message)`. If the exception also carries a validation field for controller mapping, do not switch the action test to a closure just to inspect that field. The controller feature test should mock that same exception factory and assert the field-to-message mapping with `assertRedirectBackWithErrors(...)`.

The overlapping-range example under **Range Guards** below is also the
canonical branch-specific domain-exception shape; do not duplicate it.

## Related References

- [`../README.md`](../README.md)
