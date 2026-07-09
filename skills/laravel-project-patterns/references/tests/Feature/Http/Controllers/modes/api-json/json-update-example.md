# External Identity Update Example

## When To Use

Read this focused reference when a public identity endpoint updates
provider-specific actor metadata while preserving the primary identity.

## Pattern

### Changed Provider Email

```php
it('keeps the primary email when the provider reports a changed email', function (): void {
    $actor = Actor::factory()->createOne([
        'email' => 'actor@example.test',
        'provider_email' => 'actor@example.test',
        'provider_id' => 'subject-123',
    ]);

    $this->mock(ExternalIdentityClient::class, function (
        MockInterface $mock
    ): void {
        $mock->shouldReceive('setClientId')->once();
        $mock->shouldReceive('verifyIdToken')
            ->once()
            ->with('id-token')
            ->andReturn([
                'email' => 'new-provider@example.test',
                'sub' => 'subject-123',
            ]);
    });

    $response = postJson(route('api.sessions.identity.login'), [
        'id_token' => 'id-token',
    ]);

    $response->assertOk()
        ->assertJsonPath('actor.id', $actor->public_id);

    assertDatabaseHas(Actor::class, [
        'id' => $actor->id,
        'email' => 'actor@example.test',
        'provider_email' => 'new-provider@example.test',
        'provider_id' => 'subject-123',
    ]);

    expect($actor->tokens)->toHaveCount(1);
});
```

The database assertion proves ordinary provider-field persistence. The Pest
expectation is reserved for the Eloquent token-relation count.

## Related References

- [`../api-json.md`](../api-json.md)
