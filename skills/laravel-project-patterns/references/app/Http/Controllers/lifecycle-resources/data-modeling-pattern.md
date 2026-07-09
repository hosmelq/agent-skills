# Data Modeling Pattern

## When To Use

Read this focused reference when the task involves data modeling pattern.

## Pattern

### Data Modeling Pattern

- A lifecycle controller does not require a model/table. It may manage a virtual singleton resource backed by a timestamp, boolean, enum, JSON column, external service, or relationship row.
- Add a table only when the lifecycle resource has independent attributes, history, ownership, audit requirements, or a true has-many relationship.

## Related References

- [`../lifecycle-resources.md`](../lifecycle-resources.md)
