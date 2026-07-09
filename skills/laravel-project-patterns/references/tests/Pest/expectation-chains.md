# Pest Expectation Chains

## When To Use

Use this leaf for project expectation-chain and subject-switching style.

## Pattern

### Expectation Chains

Keep related assertions in one Pest expectation chain.

When the first object has multiple checks, start from the object and use
higher-order expectations. Use `and()` when the chain changes to another
subject:

```php
expect($resolvedStatus)
    ->is($status)->toBeTrue()
    ->is_initial->toBeTrue()
    ->and($workspace->statuses)->toHaveCount(1);
```

When the first subject has only one check, pass that expression directly to
`expect()` and continue with `and()` for the next subject:

```php
expect($resolvedStatus->is($status))->toBeTrue()
    ->and($workspace->statuses)->toHaveCount(1);
```

Do not use `and($resolvedStatus)` merely to return to the same root object
after checking it. Instantiate every model or object before the expectation
chain; never put `new ...` inside `expect()` or `and()`. Extract long queries or
transformations to named variables before the expectation chain when doing so
makes the compared values clearer. Mockery methods such as `andReturn()` and
`andThrow()` are unrelated to Pest's expectation-subject switching.

## Related References

- [Parent router](../Pest.md)
