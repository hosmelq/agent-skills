# PHPStan

## When To Use

Read this focused reference when changing the repository's PHPStan and
Larastan contract.

## Pattern

### PHPStan

Run at maximum level, keep authored source paths explicit, enforce framework
and Octane checks, and require complete native type coverage:

```yaml
includes:
  - phpstan-baseline.neon
  - vendor/spaze/phpstan-disallowed-calls/disallowed-dangerous-calls.neon
  - vendor/spaze/phpstan-disallowed-calls/disallowed-execution-calls.neon
  - vendor/spaze/phpstan-disallowed-calls/disallowed-insecure-calls.neon
  - vendor/spaze/phpstan-disallowed-calls/disallowed-loose-calls.neon

parameters:
  checkAuthCallsWhenInRequestScope: true
  checkBenevolentUnionTypes: true
  checkConfigTypes: true
  checkModelProperties: false
  checkOctaneCompatibility: true
  editorUrl: 'phpstorm://open?file=%%file%%&line=%%line%%'
  errorFormat: ticketswap
  excludePaths:
    analyse:
      - bootstrap/cache
  ignoreErrors:
    -
      identifier: method.childParameterType
      path: app/Actions/Framework/ExampleFrameworkAction.php
    -
      identifier: return.deprecatedInterface
      path: app/Actions/Framework/ExamplePasswordRules.php
  level: max
  noEnvCallsOutsideOfConfig: true
  noUnnecessaryEnumerableToArrayCalls: true
  paths:
    - app
    - bootstrap
    - config
    - database
    - routes
  strictRules:
    dynamicCallOnStaticMethod: false
  type_coverage:
    constant: 100
    declare: 100
    param: 100
    property: 100
    return: 100
  type_perfect:
    no_mixed: true
    null_over_false: true
    narrow_param: true
    narrow_return: true
  tips:
    treatPhpDocTypesAsCertain: false
  tmpDir: .cache/phpstan
```

Keep each identifier/path exception narrow and source-backed. A baseline
records known diagnostics; it does not authorize broad new ignores.

## Related References

- [`../tooling-php.md`](../tooling-php.md)
