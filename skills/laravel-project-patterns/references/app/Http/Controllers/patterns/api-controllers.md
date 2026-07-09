# API Controllers

## When To Use

Read this focused reference when the task involves api controllers.

## Pattern

### API Controllers

- Match the live route declaration instead of assuming a generic API controller shape.
- Invokable API session endpoints can validate external credentials, create or update actors, issue Sanctum tokens, and return `access_token` plus a resource.
- External-token controllers may use SDK verification, cached key retrieval, HTTP retries, config checks, JWT decoding, nonce checks, account-conflict validation, and resource JSON responses.
- Access-code login endpoints validate a submitted code, mark it used, create or verify the actor as needed, issue a token, and return a resource JSON response.
- Current-actor endpoints can receive the authenticated actor through Laravel's current-actor injection attribute and return a resource.
- Current API session controllers may throw validation exceptions for external-token or session-domain failures.

## Related References

- [`../README.md`](../README.md)
