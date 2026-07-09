# Runtime Version Files

## When To Use

Read this focused reference when the task involves runtime version files.

## Pattern

### Runtime Version Files

Keep each runtime constraint on the project-owned surface that actually pins
it. A version bump must update every current surface for that runtime; do not
invent parallel version files.

| Runtime | Pin surfaces |
| --- | --- |
| PHP | Composer constraint and project runtime definition |
| PostgreSQL | Project service definition |
| Node | `.node-version` |
| Nub | `package.json` `devEngines.packageManager` |

Keep local aliases and service versions synthetic in reusable references:

```yaml
aliases:
  - example.test

name: example

php: '8.5'

secured: true

services:
  postgresql:
    port: 5432
    version: '18'
```

```text
# .node-version
24
```

```json
{
  "require": {
    "php": "^8.5"
  },
  "devEngines": {
    "packageManager": {
      "name": "nub",
      "version": "^0.6.0",
      "onFail": "warn"
    }
  }
}
```

## Related References

- [`../configuration-and-tooling.md`](../configuration-and-tooling.md)
