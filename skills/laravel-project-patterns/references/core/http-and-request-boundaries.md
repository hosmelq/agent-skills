# HTTP And Request Boundary Contracts

## When To Use

Use this router for routed controllers, delegated mutations, Form Requests,
Request-to-Input mapping, binding, authorization, validation, and public
responses.

## Pattern

Select only the applicable leaf:

- [`delegated-controller-contracts.md`](http-and-request-boundaries/delegated-controller-contracts.md):
  delegated mocks, surviving HTTP cases, return values, and mapped exceptions.
- [`form-request-ownership.md`](http-and-request-boundaries/form-request-ownership.md):
  public IDs, unknown fields, normalization, and request/action ownership.
- [`http-test-style.md`](http-and-request-boundaries/http-test-style.md):
  Mockery callbacks, `$response`, nested binding order, and observable names.

## Related References

- [`references/app/Http/Controllers/README.md`](../app/Http/Controllers/README.md)
- [`references/app/Http/Requests/README.md`](../app/Http/Requests/README.md)
- [`references/tests/Feature/Http/Controllers/README.md`](../tests/Feature/Http/Controllers/README.md)
- [`references/tests/Integration/Actions/README.md`](../tests/Integration/Actions/README.md)
