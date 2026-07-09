# Named Constraint Race Mapping

## When To Use

Use only when PostgreSQL owns the final uniqueness invariant and the action
translates one named constraint into a domain exception.

## Pattern

```php
it('maps the named reference index race to the domain error', function (): void {
    $workspace = Workspace::factory()->createOne();
    $stateRecord = StateRecord::factory()->for($workspace)->createOne();
    config()->set('database.connections.competing', config('database.connections.pgsql'));
    DB::purge('competing');
    $competingConnection = DB::connection('competing');
    $insertedCompetingRecord = false;

    DB::listen(function (QueryExecuted $query) use (
        $competingConnection,
        &$insertedCompetingRecord,
        $stateRecord,
        $workspace,
    ): void {
        if ($insertedCompetingRecord || ! str_contains($query->sql, 'lower(reference)')) {
            return;
        }

        $insertedCompetingRecord = true;
        $competingConnection->table('aggregates')->insert([
            'created_at' => now(),
            'public_id' => 'race000001',
            'reference' => 'RACE-REF',
            'state_record_id' => $stateRecord->id,
            'updated_at' => now(),
            'workspace_id' => $workspace->id,
        ]);
    });

    try {
        expect(fn () => resolve(CreateAggregate::class)->handle(
            $workspace,
            CreateAggregateInput::from([
                'children' => [['description' => 'Child', 'quantity' => 1]],
                'reference' => 'race-ref',
            ]),
        ))->toThrow(CannotCreateAggregate::class, 'aggregate.validation.reference_unique');

        $competingRecordExists = $competingConnection->table('aggregates')
            ->where('public_id', 'race000001')
            ->exists();

        expect($competingRecordExists)->toBeTrue();
    } finally {
        $competingConnection->table('aggregates')
            ->where('public_id', 'race000001')
            ->delete();
        DB::disconnect('competing');
    }
});
```

Do not catch every `QueryException`; translate only the named constraint and
rethrow all others.

## Related References

- [Aggregate create catalog](../aggregate-create-actions.md)
- [Aggregate update catalog](../aggregate-update-actions.md)
