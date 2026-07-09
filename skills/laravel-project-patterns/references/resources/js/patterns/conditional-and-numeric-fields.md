# Conditional and Numeric Fields

## When To Use

Read this focused reference when the task involves conditional and numeric fields.

## Pattern

### Conditional and Numeric Fields

Mirror backend invariants without replacing server validation:

- disable immutable fields when derived props say they cannot change;
- explain why a field is disabled;
- render conditionally required fields from the selected enum state;
- convert serialized decimal strings explicitly;
- map units exhaustively with `satisfies`.

```tsx
const unitByCode = {
  ft: 'foot',
  m: 'meter',
} satisfies Record<MeasurementUnit, Intl.NumberFormatOptions['unit']>

export function PricingFields({
  hasDependentRecords,
  resource,
  unitOptions,
}: {
  hasDependentRecords: boolean
  resource?: PricingResource
  unitOptions: Array<{label: string; value: MeasurementUnit}>
}) {
  const [roundingMode, setRoundingMode] = useState<RoundingMode>(resource?.rounding_mode ?? 'none')
  const measurementUnit = resource?.measurement_unit ?? 'm'

  return (
    <>
      <TextField
        defaultValue={resource?.code ?? ''}
        isReadOnly={resource !== undefined}
        name="code"
      >
        <Label>Code</Label>
        <Input />
        <FieldError />
        {resource !== undefined && (
          <p className="text-sm text-muted">The generated code cannot be edited.</p>
        )}
      </TextField>
      <Select
        defaultValue={measurementUnit}
        isDisabled={hasDependentRecords}
        isRequired
        name="measurement_unit"
      >
        <Label>Measurement unit</Label>
        <Select.Trigger>
          <Select.Value />
          <Select.Indicator />
        </Select.Trigger>
        <Select.Popover>
          <ListBox>
            {unitOptions.map((option) => (
              <ListBox.Item key={option.value} id={option.value} textValue={option.label}>
                {option.label}
                <ListBox.ItemIndicator />
              </ListBox.Item>
            ))}
          </ListBox>
        </Select.Popover>
        <FieldError />
        {hasDependentRecords && (
          <p className="text-sm text-muted">This value is locked after dependent records exist.</p>
        )}
      </Select>
      <NumberField
        defaultValue={Number(resource?.minimum_value ?? 0)}
        formatOptions={{
          style: 'unit',
          unit: unitByCode[measurementUnit],
          unitDisplay: 'short',
        }}
        isRequired
        minValue={0}
        name="minimum_value"
      >
        <Label>Minimum value</Label>
        <NumberField.Group>
          <NumberField.DecrementButton />
          <NumberField.Input />
          <NumberField.IncrementButton />
        </NumberField.Group>
        <FieldError />
      </NumberField>
      <Select
        defaultValue={roundingMode}
        isRequired
        name="rounding_mode"
        onChange={(value) => setRoundingMode((value ?? 'none') as RoundingMode)}
      >
        <Label>Rounding mode</Label>
        <Select.Trigger>
          <Select.Value />
          <Select.Indicator />
        </Select.Trigger>
        <Select.Popover>
          <ListBox>
            <ListBox.Item id="none">None</ListBox.Item>
            <ListBox.Item id="up">Round up</ListBox.Item>
          </ListBox>
        </Select.Popover>
        <FieldError />
      </Select>
      <NumberField
        defaultValue={
          resource?.rounding_increment != null ? Number(resource.rounding_increment) : undefined
        }
        isDisabled={roundingMode === 'none'}
        isRequired={roundingMode === 'up'}
        minValue={0}
        name="rounding_increment"
      >
        <Label>Rounding increment</Label>
        <NumberField.Group>
          <NumberField.DecrementButton />
          <NumberField.Input />
          <NumberField.IncrementButton />
        </NumberField.Group>
        <FieldError />
      </NumberField>
    </>
  )
}
```

## Related References

- [`../README.md`](../README.md)
