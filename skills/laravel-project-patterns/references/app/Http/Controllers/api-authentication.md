# API Authentication Controllers

## Purpose

This reference merges the application-side patterns for public JSON session
controllers and protected current-actor endpoints: external identity
verification, identity reconciliation, one-time-code delivery and consumption,
Sanctum token issuance, notifications, and resource responses.

## When To Use

Use it for controllers that authenticate an actor from an external credential
or one-time code, or return the currently authenticated actor. Also load:

- [`references/app/Actions/README.md`](../../Actions/README.md) when code
  generation or another step is actually delegated;
- [`references/app/Http/Resources/README.md`](../Resources/README.md) for the
  response contract;
- [`references/tests/Feature/Http/Controllers/Api/README.md`](../../../tests/Feature/Http/Controllers/Api/README.md)
  for the complete ordered feature-test matrix.

## Required Pattern

Public session endpoints validate first, then verify the credential, reconcile
provider-specific actor fields, issue a token, strip Sanctum's storage-ID
prefix, and return JSON. Protected endpoints authenticate first and serialize
only public identifiers.

Keep provider SDK and cryptographic mechanics in the provider controller when
that is the established boundary. Do not invent a verifier service,
reconciliation action, generic identity table, or transaction. The controller
owns request validation, provider failure mapping, actor reconciliation, token
issuance, status, and response shape unless the live code explicitly delegates
one of those responsibilities.

### Reference Map

- [`api-authentication/external-identity-flow.md`](api-authentication/external-identity-flow.md): External Identity Flow.
- [`api-authentication/missing-and-changed-email-rules.md`](api-authentication/missing-and-changed-email-rules.md): Missing and Changed Email Rules.
- [`api-authentication/one-time-code-request.md`](api-authentication/one-time-code-request.md): One-Time-Code Request.
- [`api-authentication/one-time-code-login.md`](api-authentication/one-time-code-login.md): One-Time-Code Login.
- [`api-authentication/protected-current-actor.md`](api-authentication/protected-current-actor.md): Protected Current Actor.

## Coverage Expectations

For each public identity provider, preserve every provider-specific verification
failure and actor-reconciliation branch supported by its controller. For one-time
codes, preserve request validation/action/notification plus expired, used,
new-actor, and existing-actor login cases. Assert token count, verification,
provider fields, code usage, notification delivery, status, and exact resource
shape where each is part of the contract.

## Do Not

- Do not use an email address as a substitute for a stable provider subject.
- Do not decode a credential without verifying its signature and claims.
- Do not call real identity providers in controller feature tests.
- Do not issue a token before identity reconciliation or code consumption
  succeeds.
- Do not expose internal IDs or provider secrets in JSON.
- Do not add an authentication failure test to a deliberately public route.

## Related References

- [`README.md`](README.md)
- [`references/app/Actions/README.md`](../../Actions/README.md)
- [`references/app/Http/Requests/README.md`](../Requests/README.md)
- [`references/app/Http/Resources/README.md`](../Resources/README.md)
- [`references/tests/Feature/Http/Controllers/Api/README.md`](../../../tests/Feature/Http/Controllers/Api/README.md)
