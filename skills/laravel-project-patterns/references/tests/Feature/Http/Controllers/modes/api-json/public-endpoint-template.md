# Public Endpoint Template

## When To Use

Read this focused reference when the task involves public endpoint template.

## Pattern

### Public Endpoint Template

```php
it('validates fields', function (array $data, array $expected): void {
    $response = postJson(route('api.sessions.code.request'), $data);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors($expected);
})->with([
    'email' => [
        'data' => [
            'recipient_email' => 'invalid',
        ],
        'expected' => [
            'recipient_email' => 'The recipient email field must be a valid email address.',
        ],
    ],
]);
```

## Related References

- [`../api-json.md`](../api-json.md)
