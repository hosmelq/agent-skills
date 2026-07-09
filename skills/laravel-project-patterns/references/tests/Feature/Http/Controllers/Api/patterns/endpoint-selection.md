# API Endpoint Selection And Order

## When To Use

Use this leaf to select public or protected endpoint coverage and its case order.

## Pattern

### Authenticated API Pattern

For protected endpoints:

1. guest request asserts `assertUnauthorized()`;
2. authenticated request uses the shared login helper;
3. response asserts public IDs, not internal IDs.

Use the canonical protected-endpoint example in
the [JSON/API controller mode](../../modes/api-json.md).


### Public Session Endpoints

For public endpoints such as `api.sessions.code.request`, `api.sessions.code.login`, `api.sessions.identity.login`, and `api.sessions.secondary-identity.login`, do not add an unauthenticated failure test unless the route becomes protected. Keep tests focused on public validation, external verification, domain branches, success JSON, and side effects.


### Endpoint Matrix and Order

| Route                                        | Observed order                                                                                                                                                                                                                                                                                                                                                               |
| -------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `POST api.sessions.identity.login`           | validation dataset; existing external identity; existing identity with missing email claim when allowed; changed email for same external ID; existing email conflict; external ID conflict through same email; account creation; missing email for new account; verification failures such as invalid token, invalid audience, expired token, invalid issuer, nonce mismatch |
| `POST api.sessions.secondary-identity.login` | validation dataset; token verification failure; existing external identity; changed email for same external ID; existing email conflict; external ID conflict through same email; account creation                                                                                                                                                                           |
| `POST api.sessions.code.request`             | validation dataset; generated-code action invocation; notification dispatch                                                                                                                                                                                                                                                                                                  |
| `POST api.sessions.code.login`               | validation dataset; expired code failure; used code failure; valid code creates an actor; valid code authenticates an existing actor                                                                                                                                                                                                                                         |
| `GET api.profile.show`                       | unauthenticated `401`; authenticated success with serialized actor public ID                                                                                                                                                                                                                                                                                                 |

Public session ordering starts with validation because no auth middleware
should block those routes. Domain failures follow validation and precede
success unless an equivalent live sibling with the same transport and identity
contract establishes a more specific flow order. Protected actor endpoints
start with unauthenticated access and then success.

## Related References

- [Parent router](../README.md)
