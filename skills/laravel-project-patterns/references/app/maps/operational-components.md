# Operational Application Components

## When To Use

Use this leaf for shared action, command, listener, notification, provider, and support boundaries.

## Pattern

### Actions, Commands, Listeners, Notifications, Providers, Support

- Actions are container-resolved classes with constructor injection and a public `handle(...)` entrypoint; integration tests resolve the action from the container and mock injected collaborators where needed.
- Actions may accept typed Data inputs when they own persistence-ready transformation, `Optional` omission, or model-default behavior. Use the input's transformed array for Eloquent writes.
- Actions receive only their business inputs and do not accept route hierarchy solely to duplicate entrypoint ownership checks. Query fresh state only for an action-owned guard, lock, or required relationship read; a create action may accept its direct business parent.
- Commands use attribute-based signatures/descriptions, typed `handle(): int`, faked HTTP/filesystem dependencies in tests, and explicit command success assertions.
- Listeners own package/framework event side effects and should have integration listener tests that trigger the real event path. Observers are not a live app pattern yet; if one is added, prove model lifecycle side effects through persisted model tests.
- Notifications implement the framework queueing pattern used locally and are normally asserted through the feature or action that sends them.
- Providers configure application-wide behavior; avoid changing provider boot logic unless the blast radius is intentional and covered by architecture, feature, or integration tests.
- Support classes need integration tests that fake the external boundary they touch, such as storage, uploaded files, HTTP clients, SDKs, or framework package hooks.

## Related References

- [Parent router](../README.md)
