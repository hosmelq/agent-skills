# Framework Contract Actions

## When To Use

Read this focused reference when the task involves framework contract actions.

## Pattern

### Framework Contract Actions

Actions that implement framework contracts may keep the framework method name instead of `handle(...)`, such as `create(...)`, `reset(...)`, or `update(...)`. They may also keep framework-owned validation, validation bags, notification side effects, and `forceFill(...)->save()` when the package contract or sibling implementation uses that shape.

Do not copy those framework exceptions into app-owned domain actions. Normal app-owned actions should use `handle(...)`, typed arguments or Data inputs, and `$model->update(...)` or relationship creates for persistence.

Keep the distinct framework-contract variants visible:

- account creation: strict input-array PHPDoc, framework validation, password
  hashing, and `create(...)`;
- password reset/update: shared password rules, the contract's named validation
  bag, and `forceFill(...)->save()`;
- profile update: separate email-change behavior, clear verification, persist,
  and send the framework re-verification notification;
- each contract keeps its framework-owned method name rather than adding a
  second `handle(...)` alias.

## Related References

- [`../README.md`](../README.md)
