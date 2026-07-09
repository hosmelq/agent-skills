# API Test And Validation Shape

## When To Use

Use this leaf for JSON controller test structure and validation datasets.

## Pattern

### File Shape

- Use `getJson()` and `postJson()` for existing API routes.
- Public session endpoints use flat `it(...)` tests when there is one invokable flow.
- Assert validation with `assertUnprocessable()` and `assertJsonValidationErrors([...])`.
- Use datasets for validation matrices.
- Use `AssertableJson` when response shape includes dynamic tokens or nested resources.
- Assert side effects explicitly when token, provider-specific actor fields,
  actor creation, access-code usage, or notification state is part of the
  contract.


### Validation Dataset Pattern

```php
it('validates fields', function (array $data, array $expected): void {
    $response = postJson(route('api.route'), $data);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors($expected);
})->with([
    'required' => [
        'data' => [],
        'expected' => ['id_token' => 'The id token field is required.'],
    ],
]);
```

## Related References

- [Parent router](../README.md)
