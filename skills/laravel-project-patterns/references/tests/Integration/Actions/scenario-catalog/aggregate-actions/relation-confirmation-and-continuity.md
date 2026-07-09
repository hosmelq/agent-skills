# Relation Confirmation And Continuity

## When To Use

Read this focused reference when the relation confirmation and continuity contract is in scope.

## Pattern

A related record may suggest other relations without authorizing implicit
assignment:

```php
it('treats related links as suggestions until independently confirmed', function (): void {
    $workspace = Workspace::factory()->createOne();
    StateRecord::factory()->selected()->for($workspace)->createOne();
    $relatedRecord = RelatedRecord::factory()->for($workspace)->createOne();

    $aggregate = resolve(CreateAggregate::class)->handle($workspace, CreateAggregateInput::from([
        'children' => [['description' => 'Child', 'quantity' => 1]],
        'related_record_id' => $relatedRecord->public_id,
    ]));

    expect($aggregate)
        ->relatedRecord->is($relatedRecord)->toBeTrue()
        ->suggestedOwner->toBeNull()
        ->suggestedMethod->toBeNull();
});
```

An update may retain its exact historical relation without making that relation
eligible for a different aggregate:

```php
it('preserves the current historical relation but rejects a new assignment', function (): void {
    $aggregate = Aggregate::factory()->withRelatedRecord()->createOne();
    $relatedRecord = $aggregate->relatedRecord;

    $relatedRecord->deactivate();
    $relatedRecord->delete();

    resolve(UpdateAggregate::class)->handle($aggregate, UpdateAggregateInput::from([
        'related_record_id' => $relatedRecord->public_id,
    ]));

    assertDatabaseHas(Aggregate::class, [
        'id' => $aggregate->id,
        'related_record_id' => $relatedRecord->id,
    ]);

    $otherAggregate = Aggregate::factory()->for($aggregate->workspace)->createOne();

    expect(fn () => resolve(UpdateAggregate::class)->handle(
        $otherAggregate,
        UpdateAggregateInput::from([
            'related_record_id' => $relatedRecord->public_id,
        ]),
    ))->toThrow(CannotUpdateAggregate::class, 'aggregate.validation.relation_unavailable');
});
```

## Related References

- [Aggregate create catalog](../aggregate-create-actions.md)
- [Aggregate update catalog](../aggregate-update-actions.md)
