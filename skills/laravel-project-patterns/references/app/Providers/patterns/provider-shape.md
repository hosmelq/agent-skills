# Provider Shape

## When To Use

Read this focused reference when the task involves provider shape.

## Pattern

### Provider Shape

- Keep provider boot logic intentional and minimal.
- Split broad boot configuration into private `configure*(): void` methods when the provider owns many independent framework settings.
- Existing provider behavior configures destructive-command protection, immutable dates, unknown-field rejection for form requests, health checks, strict Eloquent behavior, production-only model violation reporting, morph maps, resource wrapping, rate limiters, password defaults, redirect toast macros, URL behavior, Vite prefetching, Fortify views/actions, Filament panel setup, TypeScript transformer setup, and NanoID bindings.
- Prefer typed rate limiter closures where sibling provider code uses them, and preserve package-established closure shapes where sibling code does not type them. Use transliterated throttle keys when submitted identifier or actor input participates in the key. When touching Fortify limiters, compare existing Fortify provider siblings before changing the closure shape.
- Use `DeferrableProvider` plus `provides(): array` for narrow container bindings such as generated-id service aliases.
- Keep Filament panel setup fluent and declarative inside the panel provider.
- Avoid adding provider state that can leak across Octane requests.
- Do not change provider behavior without checking the broad blast radius.

## Related References

- [`../README.md`](../README.md)
