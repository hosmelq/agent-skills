# Cross-Field And Domain Validation

## When To Use

Read this focused reference when the task involves cross-field and domain validation.

## Pattern

### Cross-Field And Domain Validation

Use `after(): array` for cross-field and domain validation that needs model state and is safe to prove before the action runs. Return closures, first-class callables, or invokable validators that receive `Illuminate\Validation\Validator`. Keep callback bodies small; move multi-branch checks into private methods.

Short-circuit `after()` callbacks when base field errors already exist:

```php
/**
 * @return array<int, Closure(Validator): void>
 */
public function after(): array
{
    return [
        $this->validateActiveParentRecord(...),
    ];
}

private function validateActiveParentRecord(Validator $validator): void
{
    if ($validator->errors()->isNotEmpty()) {
        return;
    }

    if ($this->parentRecord()->deactivated_at !== null) {
        $validator->errors()->add(
            'parent_record',
            __('parent_record.validation.deactivated')
        );
    }
}
```

If a guard depends on transactional state, locks, or dependent-record checks owned by a delegated action, keep it out of the Form Request and map the action exception at the controller boundary.

For request-owned range-style domain validation, short-circuit `after()` callbacks when base field errors already exist, then add exact field errors for overlaps or duplicate open-ended ranges:

```php
private function validateRangeAvailability(Validator $validator): void
{
    if ($validator->errors()->isNotEmpty()) {
        return;
    }

    $leafRecord = $this->leafRecord();
    $minimumAmount = $this->float('minimum_amount', (float) $leafRecord->minimum_amount);
    $maximumAmount = $this->maximumAmount($leafRecord);

    if ($maximumAmount === null && $this->parentRecord()->leaves()->whereKeyNot($leafRecord)->whereNull('maximum_amount')->exists()) {
        $validator->errors()->add('maximum_amount', __('Only one open-ended range is allowed.'));
    }
}
```

Summary/general validation can use a named error bag when the entire payload is invalid rather than one field:

```php
throw ValidationException::withMessages([
    'summary' => __('Please provide at least one displayable value.'),
])->errorBag('_general');
```

## Related References

- [`../README.md`](../README.md)
