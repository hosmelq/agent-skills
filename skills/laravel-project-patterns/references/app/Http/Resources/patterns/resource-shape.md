# HTTP Resource Shape

## When To Use

Use this leaf for the base resource class and public serialization shape.

## Pattern

### Resource Shape

- Extend `JsonResource`.
- Add `@property Model $resource` PHPDoc.
- Implement `toArray(Request $request): array`.
- Add `#[Override]` on `toArray(...)`.
- Serialize public IDs as `id`.
- Keep raw internal foreign keys out of resource output unless the resource already exposes that exact field.
- Prefer public IDs, nested resources, or controller-specific props for selected relationships.

Basic shape:

```php
/**
 * @property ParentRecord $resource
 */
class ParentRecordResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    #[Override]
    public function toArray(Request $request): array
    {
        return [
            'created_at' => $this->resource->created_at,
            'deactivated_at' => $this->resource->deactivated_at,
            'id' => $this->resource->public_id,
            'name' => $this->resource->name,
            'updated_at' => $this->resource->updated_at,
        ];
    }
}
```

## Related References

- [Parent router](../README.md)
