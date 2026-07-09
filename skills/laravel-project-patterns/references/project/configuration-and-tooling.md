# Project Configuration and Tooling

## Purpose

This reference merges the reusable project-level contracts that live outside
`app/**`: environment examples, strict configuration, Composer/Nub scripts,
frontend generation/build order, TypeScript, formatters, linters, hooks, and
local process orchestration.

## When To Use

Use this reference when changing root configuration or developer tooling. Read
the live project files first; package versions, private registries, executable
names, and enabled checks remain project-specific.

## Required Pattern

### Reference Map

- [`configuration-and-tooling/environment-contract.md`](configuration-and-tooling/environment-contract.md): Environment Contract.
- [`configuration-and-tooling/strict-configuration.md`](configuration-and-tooling/strict-configuration.md): Strict Configuration.
- [`configuration-and-tooling/runtime-version-files.md`](configuration-and-tooling/runtime-version-files.md): Runtime Version Files.
- [`configuration-and-tooling/composer-and-nub-build-graph.md`](configuration-and-tooling/composer-and-nub-build-graph.md): Composer and Nub Build Graph.
- [`configuration-and-tooling/frontend-scripts-and-vite-order.md`](configuration-and-tooling/frontend-scripts-and-vite-order.md): Frontend Scripts and Vite Order.
- [`configuration-and-tooling/typescript-formatting-and-linting.md`](configuration-and-tooling/typescript-formatting-and-linting.md): TypeScript, Formatting, and Linting.
- [`configuration-and-tooling/staged-hooks.md`](configuration-and-tooling/staged-hooks.md): Staged Hooks.
- [`configuration-and-tooling/local-process-orchestration.md`](configuration-and-tooling/local-process-orchestration.md): Local Process Orchestration.

## Coverage Expectations

Changes must preserve the executable graph: setup/fresh, generation before
frontend compilation, formatting, static analysis, and tests. Validate
configuration syntax and run the narrow project script that consumes the
changed file.

## Do Not

- Do not commit secrets or private registry values.
- Do not duplicate generation/build order across scripts with divergent steps.
- Do not document generated source as authored source.
- Do not add package-manager or runtime compatibility aliases without a current
  contract.

## Related References

- [`references/project/README.md`](README.md)
- [`references/resources/js/README.md`](../resources/js/README.md)
