# Controller Transport Baselines

## When To Use

Use this leaf for web/session versus JSON baseline assertions.

## Pattern

### Baseline Assertions by Transport

Web/session:

- unauthenticated request -> `assertRedirectToRoute('login')`;
- validation failure -> `assertRedirectBackWithErrors([...])`;
- page action -> `assertOk()` plus `assertInertia(...)`;
- mutation -> redirect and toast/flash when emitted;
- delegated mutation -> mocked action invocation, minimal request-to-input mapping, redirect, and toast/flash.

JSON API:

- protected unauthenticated request -> `assertUnauthorized()`;
- public endpoint -> no auth-required test unless route middleware requires it;
- validation failure -> `assertUnprocessable()->assertJsonValidationErrors([...])`;
- success -> exact JSON contract plus side effects such as token creation, identity linking, access-code usage, notification dispatch, or public-ID serialization.

## Related References

- [Parent router](README.md)
