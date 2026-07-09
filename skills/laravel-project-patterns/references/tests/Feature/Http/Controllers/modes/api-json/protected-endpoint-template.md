# Protected Endpoint Template

## When To Use

Read this focused reference when the task involves protected endpoint template.

## Pattern

### Protected Endpoint Template

```php
describe('show', function (): void {
    it('requires authentication', function (): void {
        $response = getJson(route('api.profile.show'));

        $response->assertUnauthorized();
    });

    it('shows the authenticated actor', function (): void {
        $actor = Actor::factory()->createOne();

        login(actor: $actor);

        $response = getJson(route('api.profile.show'));

        $response->assertOk()
            ->assertJsonPath('id', $actor->public_id);
    });
});
```

## Related References

- [`../api-json.md`](../api-json.md)
