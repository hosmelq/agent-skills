# API Requests

## When To Use

Read this focused reference when the task involves api requests.

## Pattern

### API Requests

- API session requests stay small and field-focused.
- External-token endpoints require token fields and any nonce/name fields the external contract needs.
- Access-code endpoints use strict DNS email validation plus the project indisposable rule.
- Code login endpoints validate code shape and existence separately from controller-owned expiration/used checks.

```php
/**
 * @return array<string, list<string>>
 */
public function rules(): array
{
    return [
        'recipient_email' => ['required', 'string', 'max:255', 'email:strict,dns', 'indisposable'],
        'code' => ['required', 'digits:6', Rule::exists(TemporaryCode::class)],
    ];
}
```

## Related References

- [`../README.md`](../README.md)
