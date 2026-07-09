# Route Patterns

## When To Use

Read this focused reference when the task involves route patterns.

## Pattern

### Route Patterns

Choose the route registration helper by the cardinality of the resource:

- Prefer `Route::singleton()` for virtual or real child resources where only one instance can exist for the parent and the controller exposes multiple resourceful routes, especially paired `store` / `destroy` lifecycle states.
- Use `Route::resource()` for child resources with their own identity and potentially many rows.
- Use explicit routes when the controller has a single command-style endpoint, when singleton routing would obscure a local convention, when it requires a route shape Laravel's singleton helper cannot express clearly, or when it would make the surrounding route file harder to read.

Prefer singular resource URIs for singleton lifecycle resources:

```php
Route::singleton('parent-records.activation', ParentRecordActivationController::class)
    ->creatable()
    ->only(['store', 'destroy']);
```

For a `deactivated_at` lifecycle timestamp:

```php
Route::singleton('workspaces.parent-records.deactivation', ParentRecordDeactivationController::class)
    ->creatable()
    ->only(['store', 'destroy']);
```

When the lifecycle route only creates or sets a singleton state through one endpoint, prefer an explicit route instead of `Route::singleton(...)->creatable()->only(['store'])`:

```php
Route::post('workspaces/{workspace}/parent-records/{parent_record}/initial-status', [InitialParentRecordStatusController::class, 'store'])
    ->name('workspaces.parent-records.initial-status.store');
```

Singleton routing preserves the desired names and avoids a fake child parameter:

```txt
POST   /parent-records/{parent_record}/activation   parent-records.activation.store
DELETE /parent-records/{parent_record}/activation   parent-records.activation.destroy
```

Explicit routes remain valid when `Route::singleton()` does not fit the local route shape:

```php
Route::post('/parent-records/{parent_record}/activation', [ParentRecordActivationController::class, 'store'])
    ->name('parent-records.activation.store');

Route::delete('/parent-records/{parent_record}/activation', [ParentRecordActivationController::class, 'destroy'])
    ->name('parent-records.activation.destroy');
```

Do not use regular nested `resource()` for singleton lifecycle resources when it would imply a child route key:

```php
// Avoid for activation/deactivation-style singleton resources.
Route::resource('parent-records.activation', ParentRecordActivationController::class)
    ->only(['store', 'destroy']);
```

Use regular `resource()` when the child really has an identity:

```php
Route::resource('parent-records.children', ChildRecordController::class)
    ->only(['index', 'store', 'destroy']);
```

Use `PATCH` / `PUT` and `update` only when the request changes a value rather than creating/removing a lifecycle resource:

```php
Route::patch('/parent-records/{parent_record}/status', [ParentRecordStatusController::class, 'update'])
    ->name('parent-records.status.update');
```

## Related References

- [`../lifecycle-resources.md`](../lifecycle-resources.md)
