# Staged Hooks

## When To Use

Read this focused reference when the task involves staged hooks.

## Pattern

### Staged Hooks

Run the narrowest tool that owns each staged file type. Pass the staged paths
instead of formatting the entire repository.

```yaml
glob_matcher: doublestar

pre-commit:
  parallel: true
  jobs:
    - name: Frontend
      group:
        piped: true
        jobs:
          - name: Oxfmt
            glob: '**/*.{css,md,json,toml,ts,tsx,yaml,yml}'
            run: nub run fmt --no-error-on-unmatched-pattern {staged_files}
            stage_fixed: true
          - name: Oxlint
            glob: '**/*.{cjs,js,ts,tsx}'
            run: nub run fix {staged_files}
            stage_fixed: true
    - name: Backend
      group:
        piped: true
        jobs:
          - name: Rector (fix)
            glob: '**/*.php'
            exclude:
              - '**/*.blade.php'
            run: composer rector -- {staged_files}
            stage_fixed: true
          - name: Pint (fix)
            glob: '**/*.php'
            exclude:
              - '**/*.blade.php'
            run: composer pint -- {staged_files}
            stage_fixed: true
          - name: PHPStan
            glob: '**/*.php'
            exclude:
              - '**/*.blade.php'
              - 'tests/**'
            run: composer phpstan -- {staged_files}
```

## Related References

- [`../configuration-and-tooling.md`](../configuration-and-tooling.md)
