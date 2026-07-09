# Model Shape

## When To Use

Read this focused reference when the task involves model shape.

## Pattern

### Model Shape

Examples use synthetic names. Keep examples synthetic in this reference; when editing real code, preserve the live columns, route key, casts, relationships, and ownership graph from sibling models.

```php
<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasDeactivation;
use App\Models\Concerns\HasPublicId;
use Carbon\CarbonImmutable;
use Database\Factories\ParentRecordFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Override;

/**
 * @property-read int $id
 * @property-read string $public_id
 * @property-read int $workspace_id
 * @property-read null|CarbonImmutable $deactivated_at
 * @property-read string $name
 * @property-read CarbonImmutable $created_at
 * @property-read CarbonImmutable $updated_at
 * @property-read null|CarbonImmutable $deleted_at
 * @property-read Collection<int, ChildRecord> $children
 * @property-read Workspace $workspace
 */
class ParentRecord extends Model
{
    use HasDeactivation;

    /** @use HasFactory<ParentRecordFactory> */
    use HasFactory;

    use HasPublicId;
    use SoftDeletes;

    /**
     * @return HasMany<ChildRecord, $this>
     */
    public function children(): HasMany
    {
        return $this->hasMany(ChildRecord::class);
    }

    /**
     * @return BelongsTo<Workspace, $this>
     */
    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    #[Override]
    protected function casts(): array
    {
        return [
            'deactivated_at' => 'datetime',
        ];
    }
}
```

## Related References

- [`../README.md`](../README.md)
