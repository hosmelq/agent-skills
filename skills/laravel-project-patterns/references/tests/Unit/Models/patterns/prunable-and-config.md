# Prunable And Config Predicates

## When To Use

Use this leaf for focused prunable selection and configuration-driven predicates.

## Pattern

### Prunable and Config Predicates

```php
it('selects expired unused and month-old used codes for pruning', function (): void {
    $active = OneTimeCode::factory()->createOne();
    $expiredUnused = OneTimeCode::factory()->expired()->createOne();
    $usedOlder = OneTimeCode::factory()->used()->createOne([
        'used_at' => now()->subMonth(),
    ]);
    $usedRecent = OneTimeCode::factory()->used()->createOne();

    $prunableIds = new OneTimeCode()->prunable()->pluck('id');

    expect($prunableIds)
        ->toContain($expiredUnused->id, $usedOlder->id)
        ->not->toContain($active->id, $usedRecent->id);
});

it('checks panel access against configured actor emails', function (): void {
    Config::set('admin.emails', ['admin@example.test']);

    $admin = new Actor(['email' => 'admin@example.test']);
    $member = new Actor(['email' => 'member@example.test']);

    expect($admin->isAdmin())->toBeTrue()
        ->and($member->isAdmin())->toBeFalse();
});
```

The prunable example may persist the minimum rows needed to inspect the query
selection. Do not expand it into pruning execution, scheduling, or unrelated
model integration behavior.

## Related References

- [Parent router](../README.md)
