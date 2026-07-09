# Generated Identifier Scope Examples

## When To Use

Read this focused reference when the task involves generated identifier scope examples.

## Pattern

### Generated Identifier Scope Examples

Generated identifier tests should mirror the exact uniqueness contract: owner scope, normalized query, inactive-state reuse or reservation, default soft-delete behavior, cross-owner reuse, and max-attempt exceptions.

```php
it('retries when an inactive child record already uses the normalized code', function (): void {
    $parentRecord = ParentRecord::factory()->createOne([
        'code_prefix' => 'HQ-',
    ]);

    ChildRecord::factory()
        ->for($parentRecord)
        ->inactive()
        ->createOne([
            'code' => 'HQ-004992',
            'normalized_code' => 'HQ004992',
        ]);

    $this->mock(CodeGenerator::class, function (MockInterface $mock): void {
        $mock->shouldReceive('formattedId')
            ->andReturnValues(['004992', '004993']);
    });

    $code = resolve(GenerateChildRecordCode::class)->handle($parentRecord);

    expect($code)->toBe('HQ-004993');
});

it('ignores soft deleted child records when checking generated codes', function (): void {
    $parentRecord = ParentRecord::factory()->createOne([
        'code_prefix' => 'HQ-',
    ]);

    ChildRecord::factory()
        ->for($parentRecord)
        ->trashed()
        ->createOne([
            'code' => 'HQ-004992',
            'normalized_code' => 'HQ004992',
        ]);

    $this->mock(CodeGenerator::class, function (MockInterface $mock): void {
        $mock->shouldReceive('formattedId')
            ->once()
            ->andReturn('004992');
    });

    $code = resolve(GenerateChildRecordCode::class)->handle($parentRecord);

    expect($code)->toBe('HQ-004992');
});

it('ignores matching normalized codes from another Workspace', function (): void {
    $parentRecord = ParentRecord::factory()->createOne([
        'code_prefix' => 'HQ-',
    ]);

    ChildRecord::factory()->createOne([
        'code' => 'HQ-004992',
        'normalized_code' => 'HQ004992',
    ]);

    $this->mock(CodeGenerator::class, function (MockInterface $mock): void {
        $mock->shouldReceive('formattedId')
            ->once()
            ->andReturn('004992');
    });

    $code = resolve(GenerateChildRecordCode::class)->handle($parentRecord);

    expect($code)->toBe('HQ-004992');
});
```

## Related References

- [`../README.md`](../README.md)
