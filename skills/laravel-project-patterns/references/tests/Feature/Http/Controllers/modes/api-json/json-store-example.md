# Public JSON Request Example

## When To Use

Read this focused reference when a public JSON endpoint delegates generation
and sends an on-demand notification.

## Pattern

### One-Time-Code Request

```php
it('requests a one-time code', function (): void {
    Notification::fake();

    $oneTimeCode = OneTimeCode::factory()->createOne([
        'code' => '111111',
        'email' => 'actor@example.test',
    ]);

    $this->mock(GenerateOneTimeCode::class, function (
        MockInterface $mock
    ) use ($oneTimeCode): void {
        $mock->shouldReceive('handle')
            ->once()
            ->with('actor@example.test')
            ->andReturn($oneTimeCode);
    });

    $response = postJson(route('api.sessions.code.request'), [
        'email' => 'actor@example.test',
    ]);

    $response->assertOk();

    Notification::assertSentOnDemand(
        OneTimeCodeNotification::class,
        function (
            OneTimeCodeNotification $notification,
            array $channels,
            AnonymousNotifiable $notifiable
        ) use ($oneTimeCode): bool {
            return ($notifiable->routes['mail'] ?? null) === $oneTimeCode->email
                && $notification->oneTimeCode->is($oneTimeCode);
        }
    );
});
```

Public endpoints do not get an artificial authentication-failure case. The
mock proves action invocation; the notification assertion proves recipient and
model identity; the action integration suite owns code persistence and
generation rules.

## Related References

- [`../api-json.md`](../api-json.md)
