# Nested Defaults And Rollback

## When To Use

Read this focused reference when the nested defaults and rollback contract is in scope.

## Pattern

Defaults derived by the action remain separate from explicit-null behavior:

```php
it('applies selected state timestamp and current relation defaults', function (): void {
    $workspace = Workspace::factory()->createOne();
    $selectedState = StateRecord::factory()->selected()->for($workspace)->createOne();
    $receivedLocation = Location::factory()->for($workspace)->createOne();

    $aggregate = resolve(CreateAggregate::class)->handle($workspace, CreateAggregateInput::from([
        'children' => [[
            'description' => 'Default child',
            'quantity' => 1,
        ]],
        'received_location_id' => $receivedLocation->public_id,
    ]));

    expect($aggregate)
        ->stateRecord->is($selectedState)->toBeTrue()
        ->received_at->equalTo(now())->toBeTrue()
        ->receivedLocation->is($receivedLocation)->toBeTrue()
        ->currentLocation->is($receivedLocation)->toBeTrue()
        ->children->toHaveCount(1);
});
```

A later nested failure rolls back every earlier write:

```php
it('rolls back the aggregate and earlier children when a later child fails', function (): void {
    $workspace = Workspace::factory()->createOne();
    StateRecord::factory()->selected()->for($workspace)->createOne();

    expect(fn () => resolve(CreateAggregate::class)->handle(
        $workspace,
        CreateAggregateInput::from([
            'children' => [
                ['description' => 'Valid first child', 'quantity' => 1],
                ['description' => 'Invalid second child', 'quantity' => 0],
            ],
        ]),
    ))->toThrow(QueryException::class);

    assertDatabaseCount(Aggregate::class, 0);
    assertDatabaseCount(ChildRecord::class, 0);
});
```

## Related References

- [Aggregate create catalog](../aggregate-create-actions.md)
