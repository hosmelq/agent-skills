# Tooling PHP

## Purpose

Route root PHP quality-tool changes to the smallest applicable reference
without loading every tool configuration.

## When To Use

Use this reference when changing formatter, refactor, static-analysis, or
dependency-analysis configuration owned by the repository.

## Required Pattern

Root tooling is declarative, uses strict types for PHP configuration, and names
the source, cache, generated, and excluded paths it owns.

### Reference Map

- [`tooling-php/pint.md`](tooling-php/pint.md): PHP formatter rules.
- [`tooling-php/rector.md`](tooling-php/rector.md): automated refactor sets,
  paths, and intentional skips.
- [`tooling-php/phpstan.md`](tooling-php/phpstan.md): maximum static analysis,
  type coverage, and narrow exceptions.
- [`tooling-php/dependency-analysis.md`](tooling-php/dependency-analysis.md):
  dependency scanning and evidence-backed unused-dependency exceptions.

## Coverage Expectations

Read the live config before editing it. Preserve every enabled rule or set
unless the task explicitly changes that contract, and run the individual tool
that consumes the changed file.

## Do Not

- Do not replace an authored strict ruleset with a broad preset for brevity.
- Do not add ignore paths or package exceptions without a current diagnostic.
- Do not scan generated/cache output as authored source.

## Related References

- [`../README.md`](../README.md)
- [`../configuration-and-tooling.md`](../configuration-and-tooling.md)
