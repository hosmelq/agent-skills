# Generated Values And Resolvers

## When To Use

Read this focused reference when the task involves generated values and resolvers.

## Pattern

### Generated Values And Resolvers

For generated identifiers, keep the generated-value logic private and check uniqueness against the specific application contract. Scope generated values through the owner when the value is owner-scoped; normalize before querying when the persisted column is normalized. Do not add an active-state filter unless the domain allows reuse from inactive records; default Eloquent soft-delete scopes are enough when soft-deleted records may be ignored. For globally unique single-use codes, clean up existing unused rows for the submitted identifier before creating a new one.

```php
class GenerateChildRecordCode
{
    public const int MAX_RETRY_ATTEMPTS = 20;

    public function __construct(private readonly CodeGenerator $codeGenerator)
    {
    }

    public function handle(ParentRecord $parentRecord): string
    {
        $attempts = 0;

        while ($attempts < self::MAX_RETRY_ATTEMPTS) {
            $code = $this->generateCode($parentRecord);

            ++$attempts;

            if (! $this->codeExists($parentRecord, $code)) {
                return $code;
            }
        }

        throw CannotGenerateChildRecordCode::maxAttempts($attempts);
    }

    private function codeExists(ParentRecord $parentRecord, string $code): bool
    {
        return ChildRecord::query()
            ->where('parent_record_id', $parentRecord->id)
            ->where('normalized_code', ChildRecord::normalizeCode($code))
            ->exists();
    }
}
```

Globally unique single-use codes add transactional cleanup and expiry before
creation:

```php
public function handle(string $identifier): OneTimeCode
{
    return DB::transaction(function () use ($identifier): OneTimeCode {
        OneTimeCode::query()
            ->where('identifier', $identifier)
            ->whereNull('used_at')
            ->delete();

        for ($attempt = 1; $attempt <= self::MAX_RETRY_ATTEMPTS; $attempt++) {
            $code = $this->codeGenerator->generate();

            if (OneTimeCode::query()->where('code', $code)->exists()) {
                continue;
            }

            return OneTimeCode::query()->create([
                'code' => $code,
                'expires_at' => now()->addMinutes(10),
                'identifier' => $identifier,
            ]);
        }

        throw CannotGenerateOneTimeCode::maxAttempts(
            self::MAX_RETRY_ATTEMPTS,
        );
    });
}
```

Already-used codes continue reserving their value; cleanup removes only unused
codes for the submitted identifier. Owner-scoped generators, global
single-use-code generators, and nullable active-only resolvers are separate
contracts and need separate integration matrices.

Resolver actions can return nullable models when the domain contract is finder-only:

```php
public function handle(ParentRecord $parentRecord, string $code): null|ChildRecord
{
    return ChildRecord::query()
        ->where('parent_record_id', $parentRecord->id)
        ->where('normalized_code', ChildRecord::normalizeCode($code))
        ->whereNull('deactivated_at')
        ->first();
}
```

## Related References

- [`../README.md`](../README.md)
