# Index Pages: Collection And Mutation States

## When To Use

Read this leaf when collection and mutation states is in scope.

## Pattern

Index pages merge five distinct states:

- empty collection;
- populated accessible table;
- paginated navigation including ellipses;
- pending row mutation;
- lifecycle/action availability based on serialized state.

```tsx
export default function ChildRecordIndex({
  childRecords,
  parentRecord,
}: {
  childRecords: LaravelPaginator<ChildRecordResource>
  parentRecord: ParentRecordResource
}) {
  const [deletingId, setDeletingId] = useState<null | string>(null)

  if (childRecords.data.length === 0) {
    return (
      <EmptyState
        actionHref={create({parent_record: parentRecord.id}).url}
        title="No child records"
      />
    )
  }

  function destroyRecord(childRecord: ChildRecordResource) {
    if (!window.confirm('Delete this record?')) {
      return
    }

    setDeletingId(childRecord.id)

    router.delete(
      destroy({
        parent_record: parentRecord.id,
        child_record: childRecord.id,
      }).url,
      {
        preserveScroll: true,
        onFinish: () => setDeletingId(null),
      },
    )
  }

  return (
    <Table>
      <Table.ScrollContainer>
        <Table.Content aria-label="Child records">
          <Table.Header>
            <Table.Column isRowHeader>Name</Table.Column>
            <Table.Column>Actions</Table.Column>
          </Table.Header>
          <Table.Body>
            {childRecords.data.map((childRecord) => (
              <Table.Row id={childRecord.id} key={childRecord.id}>
                <Table.Cell>
                  <Link
                    href={
                      show({
                        parent_record: parentRecord.id,
                        child_record: childRecord.id,
                      }).url
                    }
                  >
                    {childRecord.name}
                  </Link>
                </Table.Cell>
                <Table.Cell>
                  <Button
                    isDisabled={
                      childRecord.deactivated_at !== null || deletingId === childRecord.id
                    }
                    onPress={() => destroyRecord(childRecord)}
                    variant="danger"
                  >
                    Delete
                  </Button>
                </Table.Cell>
              </Table.Row>
            ))}
          </Table.Body>
        </Table.Content>
      </Table.ScrollContainer>
      {childRecords.meta.total > childRecords.meta.per_page && (
        <Table.Footer>
          <Paginator pagination={childRecords} />
        </Table.Footer>
      )}
    </Table>
  )
}
```

## Related References

- [`../index-pages.md`](../index-pages.md)
