# Create/Edit Shell and Shared Form

## When To Use

Read this focused reference when the task involves create/edit shell and shared form.

## Pattern

### Create/Edit Shell and Shared Form

Create and edit pages are typed shells. Put the shared field graph and
Wayfinder action selection in one form.

```tsx
import {store, update} from '#/actions/App/Http/Controllers/ChildRecordController'
import type {ChildRecordResource, ParentRecordResource} from '#/types'

interface ChildRecordFormProps {
  childRecord?: ChildRecordResource
  parentRecord: ParentRecordResource
}

export function ChildRecordForm({childRecord, parentRecord}: ChildRecordFormProps) {
  const action = childRecord
    ? update({
        parent_record: parentRecord.id,
        child_record: childRecord.id,
      })
    : store({
        parent_record: parentRecord.id,
      })

  return (
    <ServerForm action={action} options={{preserveScroll: true}}>
      {({processing}) => (
        <>
          <TextField defaultValue={childRecord?.name ?? ''} isRequired name="name">
            <Label>Name</Label>
            <Input />
            <FieldError />
          </TextField>
          <Button isPending={processing} type="submit">
            {childRecord ? 'Save changes' : 'Create record'}
          </Button>
        </>
      )}
    </ServerForm>
  )
}
```

```tsx
export default function CreateChildRecordPage(props: {parentRecord: ParentRecordResource}) {
  return (
    <PageLayout title="Create child record">
      <ChildRecordForm parentRecord={props.parentRecord} />
    </PageLayout>
  )
}
```

```tsx
export default function EditChildRecordPage(props: {
  childRecord: ChildRecordResource
  parentRecord: ParentRecordResource
}) {
  return (
    <PageLayout title="Edit child record">
      <ChildRecordForm childRecord={props.childRecord} parentRecord={props.parentRecord} />
    </PageLayout>
  )
}
```

Generated Wayfinder imports remain typed. Do not construct route URLs by string
concatenation.

## Related References

- [`../README.md`](../README.md)
