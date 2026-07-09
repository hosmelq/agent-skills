# Request and Assertion Mapping

## When To Use

Read this focused reference when the task involves request and assertion mapping.

## Pattern

### Request and Assertion Mapping

| Web/session                          | Current JSON API                                         |
| ------------------------------------ | -------------------------------------------------------- |
| `get`, `post`, `patch`, `delete`     | `getJson`, `postJson`                                    |
| guest web endpoint -> login redirect | protected JSON endpoint -> `assertUnauthorized()`        |
| `assertRedirectBackWithErrors(...)`  | `assertUnprocessable()->assertJsonValidationErrors(...)` |
| `assertOk() + assertInertia(...)`    | `assertOk()` plus exact JSON and side-effect assertions  |
| redirect + toast                     | JSON payload/status contract                             |

Do not add `patchJson()` or `deleteJson()` examples until an actual API route
uses them.

## Related References

- [`../api-json.md`](../api-json.md)
