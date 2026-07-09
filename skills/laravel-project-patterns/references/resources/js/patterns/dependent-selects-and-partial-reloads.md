# Dependent Selects and Partial Reloads

## When To Use

Read this focused reference when the task involves dependent selects and partial reloads.

## Pattern

### Dependent Selects and Partial Reloads

When one selection changes the available options for another:

1. keep the selected parent value locally;
2. issue a partial reload with `data` and `only`;
3. preserve the rest of the page state;
4. clear stale child input through local state or a keyed remount;
5. disable the child control when no options exist.

```tsx
import {
  Autocomplete,
  EmptyState,
  Label,
  ListBox,
  SearchField,
  Select,
  useFilter,
} from '@heroui/react'
import {router} from '@inertiajs/react'
import {useState} from 'react'

interface Option {
  label: string
  value: string
}

export function DependentFields({
  childOptions,
  initialChildCode,
  initialParentCode,
  parentOptions,
}: {
  childOptions: Option[]
  initialChildCode?: null | string
  initialParentCode?: null | string
  parentOptions: Option[]
}) {
  const {contains} = useFilter({sensitivity: 'base'})
  const initialCode = initialParentCode ?? ''
  const [selectedParentCode, setSelectedParentCode] = useState(initialCode)

  return (
    <>
      <Select
        defaultValue={initialCode || undefined}
        isRequired
        name="parent_code"
        onChange={(value) => {
          const parentCode = (value ?? '') as string

          setSelectedParentCode(parentCode)

          router.reload({
            data: {parent_code: parentCode},
            only: ['childOptions'],
          })
        }}
      >
        <Label>Parent option</Label>
        <Select.Trigger>
          <Select.Value />
          <Select.Indicator />
        </Select.Trigger>
        <Select.Popover>
          <ListBox>
            {parentOptions.map((option) => (
              <ListBox.Item key={option.value} id={option.value} textValue={option.label}>
                {option.label}
                <ListBox.ItemIndicator />
              </ListBox.Item>
            ))}
          </ListBox>
        </Select.Popover>
      </Select>
      <Autocomplete
        key={`child-${selectedParentCode}`}
        defaultValue={
          selectedParentCode === initialCode ? (initialChildCode ?? undefined) : undefined
        }
        isDisabled={childOptions.length === 0}
        name="child_code"
      >
        <Label>Child option</Label>
        <Autocomplete.Trigger>
          <Autocomplete.Value />
          <Autocomplete.ClearButton type="button" />
          <Autocomplete.Indicator />
        </Autocomplete.Trigger>
        <Autocomplete.Popover>
          <Autocomplete.Filter filter={contains}>
            <SearchField variant="secondary">
              <SearchField.Group>
                <SearchField.SearchIcon />
                <SearchField.Input placeholder="Search options..." />
                <SearchField.ClearButton />
              </SearchField.Group>
            </SearchField>
            <ListBox renderEmptyState={() => <EmptyState>No results found</EmptyState>}>
              {childOptions.map((option) => (
                <ListBox.Item key={option.value} id={option.value} textValue={option.label}>
                  {option.label}
                  <ListBox.ItemIndicator />
                </ListBox.Item>
              ))}
            </ListBox>
          </Autocomplete.Filter>
        </Autocomplete.Popover>
      </Autocomplete>
    </>
  )
}
```

The controller feature test owns the full page prop contract and the focused
`reloadOnly(...)` branch.

## Related References

- [`../README.md`](../README.md)
