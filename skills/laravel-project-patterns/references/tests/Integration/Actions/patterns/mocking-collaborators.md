# Mocking Collaborators

## When To Use

Read this focused reference when the task involves mocking collaborators.

## Pattern

### Mocking Collaborators

Mock injected collaborators through the container:

```php
$this->mock(CodeGenerator::class, function (MockInterface $mock): void {
    $mock->shouldReceive('formattedId')
        ->once()
        ->with($expectedAlphabet, 6)
        ->andReturn('111111');
});
```

Assert retry behavior with `andReturnValues([...])` or `times(...)` when the action has retry limits.

## Related References

- [`../README.md`](../README.md)
