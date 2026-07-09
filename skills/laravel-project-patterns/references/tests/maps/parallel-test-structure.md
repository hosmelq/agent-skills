# Parallel Test Structure

## When To Use

Use this leaf after finding an equivalent sibling test with the same precondition,
operation, and observable outcome.

## Pattern

Equivalent scenarios use the same:

- grammatical name template and complete domain nouns;
- fixture, action, and assertion order;
- factory relationship style and assertion type.

Change only the entity names and qualifiers required by a real domain
difference. Do not add a test merely because another module has one.

Arrange relationships explicitly so the relation under test is first loaded
after the action. Do not preload it and add `refresh()` when the same behavior
can use the simpler sibling structure.

```php
it('deactivates a referenced parent record without breaking existing child records', function (): void {
    $parentRecord = ParentRecord::factory()->createOne();
    $childRecord = ChildRecord::factory()
        ->for($parentRecord)
        ->createOne();

    resolve(DeactivateParentRecord::class)->handle($parentRecord);

    expect($childRecord->parentRecord->is($parentRecord))->toBeTrue();
});

it('deactivates a referenced child record without breaking existing leaf records', function (): void {
    $childRecord = ChildRecord::factory()->createOne();
    $leafRecord = LeafRecord::factory()
        ->for($childRecord)
        ->createOne();

    resolve(DeactivateChildRecord::class)->handle($childRecord);

    expect($leafRecord->childRecord->is($childRecord))->toBeTrue();
});
```

If the second action rejects only a default child record, keep the shared
template and add only that meaningful qualifier, for example
`deactivates a referenced non-default child record without breaking existing
leaf records`.

### Do Not

- Do not shorten one entity to `record` when its sibling uses the complete
  domain noun.
- Do not replace equivalent wording with synonyms such as `keeps`, `preserves`,
  or `retains` without a different observable contract.
- Do not retain different fixture or assertion shapes inherited accidentally
  from separate implementations.

## Related References

- [Test design and style](../../core/test-design-and-style.md)
- [Persistence assertions](persistence-assertions.md)
