# API Session Validation Snippets

## Purpose

This reference defines validation snippets for public JSON authentication endpoints.

## When To Use

Use this reference for public API session, external identity, access-code request, and access-code login endpoints. Do not add `requires authentication` tests unless the route is protected.

## Required Pattern

Validation datasets for public session endpoints are intentionally compact. Verification failures, identity conflicts, expired codes, used codes, and account creation are domain cases after validation.

Suggested flow order:

- external identity primary: validation, existing identity success, missing contact address for linked identity when supported, changed contact address for same external ID, existing contact-address conflict, external ID conflict, account creation, missing contact address for new account, then verification failures;
- external identity secondary: validation, verification failure, existing identity success, changed contact address for same external ID, existing contact-address conflict, external ID conflict, account creation;
- access-code request: validation, generated-code action invocation, notification dispatch;
- access-code login: validation, expired code, used code, create actor, existing actor.

### Focused References

- [Request Access-Code Validation](api-login-validation/request-code.md): Use this leaf for the public access-code request validation dataset.
- [Access-Code Login Validation](api-login-validation/login-code.md): Use this leaf for the public access-code login validation dataset.
- [External Identity Login Validation](api-login-validation/login-identity.md): Use this leaf for the public external identity login validation dataset.

## Coverage Expectations

Use the live controller, routes, form requests, resources, and equivalent
transport/identity sibling tests to decide the complete auth matrix. Request
endpoints also prove action invocation and notification dispatch. Login
endpoints cover expired codes, used codes, new-actor success, existing-actor
success, token response shape, and persisted `used_at`/contact verification
side effects.

## Do Not

- Do not contradict the skill non-negotiables or project conventions.
- Do not drop applicable validation coverage when adapting examples.
- Do not use real external service, route, or entity names in examples.

## Related References

- [`references/tests/Feature/Http/Controllers/README.md`](../README.md)
