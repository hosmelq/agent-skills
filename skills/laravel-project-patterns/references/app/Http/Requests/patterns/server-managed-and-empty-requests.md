# Server-Managed And Empty Requests

## When To Use

Read this focused reference when the task involves server-managed and empty requests.

## Pattern

### Server-Managed And Empty Requests

Use `missing` for fields that must not be submitted at all and `prohibited` for fields that should produce an explicit field error when submitted:

```php
'generated_code' => ['missing'],
'related_record_id' => ['prohibited'],
```

Do not create or keep empty Form Request classes just to give a controller method a named request type, to reject unknown payload fields, or to look consistent with store/update actions. If the controller does not consume validated input and the request has no request-owned hooks, use route models and action dependencies directly instead of type-hinting a useless request.

An empty `rules()` result is acceptable only inside a request that carries
other real request-owned behavior, such as authorization,
`prepareForValidation()`, `after()` validation, or custom
messages/attributes. Do not use an empty rule array by itself as the reason to
keep a request class.

## Related References

- [`../README.md`](../README.md)
