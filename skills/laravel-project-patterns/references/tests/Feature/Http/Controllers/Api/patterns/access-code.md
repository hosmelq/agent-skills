# Access-Code Endpoints

## When To Use

Use this leaf for access-code request and login scenarios.

## Pattern

### Access-Code Endpoints

For access-code request/login endpoints:

- request endpoints prove validation, action invocation, and notification dispatch;
- login endpoints reject expired and already-used codes before success;
- success marks the code as used, authenticates or creates the actor as required, and asserts token response shape.

## Related References

- [Parent router](../README.md)
