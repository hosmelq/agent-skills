# Protected vs Public Endpoints

## When To Use

Read this focused reference when the task involves protected vs public endpoints.

## Pattern

### Protected vs Public Endpoints

- Protected endpoint: add `requires authentication`, use `assertUnauthorized()`, then authenticate and assert public IDs in JSON.
- Public endpoint: do not add auth-required tests unless middleware protects it. Start with validation, then domain failures, then success and side effects.

Current public examples use neutral route shapes:

- `api.sessions.identity.login`
- `api.sessions.secondary-identity.login`
- `api.sessions.code.request`
- `api.sessions.code.login`

Protected actor example:

- `api.profile.show`

Do not infer JSON API equivalents for web routes unless the API route file exposes them.

## Related References

- [`../api-json.md`](../api-json.md)
