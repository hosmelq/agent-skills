# Transport / API Patterns

## When To Use

Read this focused reference when the task involves transport / api patterns.

## Pattern

### Transport / API Patterns

- Web/session uses route helpers, authenticated sessions, redirects, validation errors, Inertia contracts, persistence assertions, and toast/flash assertions.
- Public JSON session endpoints start with validation because no auth middleware should block them.
- External verification failures are domain cases after validation passes.
- Existing external identities should authenticate the existing actor and return the expected token/JSON contract.
- Changed external email should not create a new actor when the identity match controls the contract.
- Email conflicts and external-id conflicts need separate branches.
- Missing external claims need a dedicated failure branch when the controller handles it.
- Access-code request endpoints assert action invocation and notification dispatch.
- Access-code login endpoints reject expired and used codes, then prove new-actor and existing-actor success branches.
- Protected actor endpoints assert guest `401`, then authenticated public-ID JSON.

## Related References

- [`../pattern-catalog.md`](../pattern-catalog.md)
