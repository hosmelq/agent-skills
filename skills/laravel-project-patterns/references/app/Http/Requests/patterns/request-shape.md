# Request Shape

## When To Use

Read this focused reference when the task involves request shape.

## Pattern

### Request Shape

- Use typed `rules(): array` methods with precise PHPDoc array shapes.
- Use `Rule::enum(...)`, scoped `Rule::unique(...)`, scoped `Rule::exists(...)`, `withoutTrashed()`, and `ignore($model)` where needed.
- Use route parameters through the same pattern as sibling requests. Prefer `#[RouteParameter(...)]` when a rule scopes to a bound parent or leaf model.
- When a request repeatedly needs a route-bound model, extract it through a small private helper that asserts the type.
- Store requests usually use `required`; update requests often use `sometimes|required`.
- Keep Form Requests focused on validation, normalization, and request-owned cross-field/domain validation. Do not add `input()` or `payload()` helpers when a Data input can be constructed directly from `$request->validated()` at the controller boundary.
- Application boot calls `FormRequest::failOnUnknownFields()`. If a field is not part of the endpoint contract, leave it out of `rules()` and let unknown-field validation reject submitted input.
- Add `missing` or `prohibited` only when the endpoint intentionally exposes a field-specific validation error for that submitted field. Do not add `exclude` to silently drop server-managed input.

Route-parameter helper pattern:

```php
private function parentRecord(): ParentRecord
{
    $parentRecord = $this->route('parent_record');

    assert($parentRecord instanceof ParentRecord);

    return $parentRecord;
}
```

Route-parameter injection pattern:

```php
/**
 * @return array<string, list<string|Stringable|ValidationRule>>
 */
public function rules(
    #[RouteParameter('workspace')] Workspace $workspace,
    #[RouteParameter('parent_record')] ParentRecord $parentRecord
): array {
    return [
        'name' => [
            'sometimes',
            'required',
            'string',
            'max:255',
            Rule::unique(ParentRecord::class)
                ->ignore($parentRecord)
                ->where('workspace_id', $workspace->id)
                ->withoutTrashed(),
        ],
    ];
}
```

Scoped uniqueness pattern:

```php
'name' => [
    'sometimes',
    'required',
    'string',
    'max:255',
    Rule::unique(ParentRecord::class)
        ->ignore($this->parentRecord())
        ->where('workspace_id', $this->workspace()->id)
        ->withoutTrashed(),
],
```

Public-ID selectable relation pattern:

```php
'related_record_id' => [
    'required',
    'string',
    Rule::exists(RelatedRecord::class, 'public_id')
        ->where('workspace_id', $this->workspace()->id)
        ->whereNull('deactivated_at')
        ->withoutTrashed(),
],
```

Conditional rule pattern:

```php
'dependent_amount' => [
    Rule::requiredIf($this->input('example_mode') === ExampleMode::Advanced->value),
    'nullable',
    'decimal:0,4',
    'gt:0',
],
'minimum_amount' => [
    'required',
    'decimal:0,4',
    'gte:0',
    Rule::when($this->filled('maximum_amount'), 'lte:maximum_amount'),
],
```

Contact and localized field pattern:

```php
'contact_email' => ['nullable', 'string', 'max:255', 'email:strict,dns', 'indisposable'],
'contact_phone_number' => [
    'nullable',
    'string',
    'max:255',
    new Phone()->country(CountryCode::values()),
],
'timezone' => ['sometimes', 'required', 'string', 'timezone'],
```

## Related References

- [`../README.md`](../README.md)
