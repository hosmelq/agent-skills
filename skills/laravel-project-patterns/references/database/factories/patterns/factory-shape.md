# Factory Shape

## When To Use

Read this focused reference when the task involves factory shape.

## Pattern

### Factory Shape

The names below are shape-only placeholders. Keep examples synthetic in this reference; when editing real code, preserve the live ownership graph, defaults, casts, and constraints from sibling factories.

```php
<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\ChildRecord;
use App\Models\ParentRecord;
use App\Models\RelatedRecord;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ChildRecord>
 */
class ChildRecordFactory extends Factory
{
    public function deactivated(): static
    {
        return $this->state(['deactivated_at' => now()]);
    }

    public function definition(): array
    {
        $minimumAmount = fake()->randomFloat(2, 0, 10);

        return [
            'parent_record_id' => ParentRecord::factory(),

            'deactivated_at' => null,
            'maximum_amount' => fake()->optional()->randomFloat(2, $minimumAmount, 20),
            'minimum_amount' => $minimumAmount,
            'name' => fake()->word(),
        ];
    }
}
```

## Related References

- [`../README.md`](../README.md)
