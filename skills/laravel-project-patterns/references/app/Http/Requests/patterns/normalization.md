# Normalization

## When To Use

Read this focused reference when the task involves normalization.

## Pattern

### Normalization

Use `prepareForValidation()` for derived input, partial-update normalization, and relationship inference. Do not add `prepareForValidation()` only to translate a valid public ID into an internal database ID. If validation can target the public ID column and the controller can resolve the model after validation, keep that conversion at the controller boundary.

Nullable field pairs use reciprocal `required_with`. On partial updates, add reciprocal `present_with` so explicitly submitting one side, including `null`, also requires the other key; omission of both remains valid.

Store nullable-pair rules:

```php
'start_value' => ['nullable', 'numeric', 'required_with:end_value'],
'end_value' => ['nullable', 'numeric', 'required_with:start_value'],
```

Partial-update nullable-pair rules:

```php
'start_value' => ['nullable', 'numeric', 'required_with:end_value', 'present_with:end_value'],
'end_value' => ['nullable', 'numeric', 'required_with:start_value', 'present_with:start_value'],
```

Address-like requests infer or clear `subdivision_code` from `region_code` during partial updates so subdivision validation still scopes to the effective region.

Address-like update normalization:

```php
#[Override]
protected function prepareForValidation(): void
{
    $childRecord = $this->childRecord();

    if ($this->filled('subdivision_code') && $this->isNotFilled('region_code')) {
        $this->merge([
            'region_code' => $childRecord->region_code->value,
        ]);
    }

    if (
        $this->filled('region_code')
        && $this->input('region_code') !== $childRecord->region_code->value
        && $this->isNotFilled('subdivision_code')
    ) {
        $this->merge([
            'subdivision_code' => null,
        ]);
    }
}
```

Partial min/max pairs should merge the missing side from the route-bound model when the submitted side needs it for validation:

```php
#[Override]
protected function prepareForValidation(): void
{
    $childRecord = $this->childRecord();

    if ($this->filled('minimum_amount') && $this->missing('maximum_amount') && $childRecord->maximum_amount !== null) {
        $this->merge([
            'maximum_amount' => $childRecord->maximum_amount,
        ]);
    }

    if ($this->filled('maximum_amount') && $this->missing('minimum_amount')) {
        $this->merge([
            'minimum_amount' => $childRecord->minimum_amount,
        ]);
    }
}
```

When a nullable upper bound can be explicitly cleared with `null`, use `has()` instead of `filled()` so the request still merges the stored lower bound for validation:

```php
#[Override]
protected function prepareForValidation(): void
{
    $leafRecord = $this->leafRecord();

    if ($this->filled('minimum_amount') && $leafRecord->maximum_amount !== null) {
        $this->mergeIfMissing(['maximum_amount' => $leafRecord->maximum_amount]);
    }

    if ($this->has('maximum_amount')) {
        $this->mergeIfMissing(['minimum_amount' => $leafRecord->minimum_amount]);
    }
}
```

Value normalization should be conservative: normalize only when the input is present and valid enough to transform safely.

Conservative contact normalization:

```php
#[Override]
protected function prepareForValidation(): void
{
    if ($this->isNotFilled('contact_phone_number')) {
        return;
    }

    $phoneNumber = new PhoneNumber($this->string('contact_phone_number')->toString(), CountryCode::values());

    if (! $phoneNumber->isValid()) {
        return;
    }

    try {
        $formattedPhoneNumber = $phoneNumber->formatE164();
    } catch (NumberParseException) {
        return;
    }

    $this->merge([
        'contact_phone_number' => $formattedPhoneNumber,
    ]);
}
```

## Related References

- [`../README.md`](../README.md)
