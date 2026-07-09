# Console Command Shape

## When To Use

Use this leaf for the application console-command implementation contract.

## Pattern

### Command Shape

- Use command attributes for signature and description when sibling commands do.
- Implement `handle(): int`.
- Return `Command::SUCCESS` or the appropriate framework command code.
- Read config with typed config helpers.
- Use framework HTTP/file abstractions and package helpers rather than shelling out.
- Write operator-facing command output through `$this->components`.

## Related References

- [Parent router](../README.md)
