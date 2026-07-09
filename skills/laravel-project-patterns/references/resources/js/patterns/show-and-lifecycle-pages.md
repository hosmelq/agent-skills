# Show and Lifecycle Pages

## When To Use

Read this focused reference when the task involves show and lifecycle pages.

## Pattern

### Show and Lifecycle Pages

Use typed detail definitions, explicit nullable fallbacks, and centralized
formatters. Gate lifecycle actions by the resource state and clear pending
state in `onFinish`.

```tsx
const details = [
  ['Name', record.name],
  ['Description', record.description ?? '—'],
  ['Created', formatDate(record.created_at)],
  ['Range', formatRange(record.minimum_value, record.maximum_value)],
] as const
```

```tsx
function changeLifecycle() {
  const nextAction = record.deactivated_at
    ? reactivate({parent_record: record.id})
    : deactivate({parent_record: record.id})

  if (!window.confirm('Change this record state?')) {
    return
  }

  setPending(true)

  router.visit(nextAction.url, {
    method: nextAction.method,
    preserveScroll: true,
    onFinish: () => setPending(false),
  })
}
```

Related-resource links use public IDs through Wayfinder. A deleted or
deactivated related record may remain visible on edit/show only when the
backend deliberately supplies that stored relationship for continuity.

## Related References

- [`../README.md`](../README.md)
