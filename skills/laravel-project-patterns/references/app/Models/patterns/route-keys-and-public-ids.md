# Route Keys and Public IDs

## When To Use

Read this focused reference when the task involves route keys and public ids.

## Pattern

### Route Keys and Public IDs

- `HasPublicId` provides `getRouteKeyName(): string` returning `public_id` and `uniqueIds(): array` returning `['public_id']`.
- `HasPublicId` also provides `findByPublicId(...)` and `findOrFailByPublicId(...)`; use these helpers instead of hand-written public ID queries when resolving validated public IDs.
- Public IDs are 10-character alphanumeric Nano IDs stored in case-insensitive text columns with database format checks; route binding accepts case-insensitive public IDs.
- Use slug route keys only for models that are explicitly slug-backed. Override `getRouteKeyName()` to return `slug` and test slug generation/stability.
- Do not expose numeric IDs in routes when sibling domain models use `public_id` or slug.
- Keep internal integer IDs in database columns and serialized public IDs in resources/routes/form values. Convert at boundaries instead of leaking internal IDs into external contracts.

Slug override pattern:

```php
#[Sluggable(from: 'name', to: 'slug', onUpdate: false)]
class Workspace extends Model
{
    use HasPublicId;

    #[Override]
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
```

For slug-backed models, generate the slug from the display name once, keep route binding on `slug`, and prove creation plus non-regeneration on update in `tests/Integration/Models`.

Manual route-key override pattern:

```php
#[Override]
public function getRouteKeyName(): string
{
    return 'slug';
}
```

Auth/current-`Workspace` model pattern:

```php
#[Hidden(['password', 'remember_token'])]
class Actor extends Authenticatable implements AdminPanelUser
{
    use HasApiTokens;

    /** @use HasFactory<ActorFactory> */
    use HasFactory;

    use HasPublicId;
    use Notifiable;

    public function belongsToWorkspace(null|Workspace $workspace): bool
    {
        if (! $workspace instanceof Workspace) {
            return false;
        }

        if ($this->ownsWorkspace($workspace)) {
            return true;
        }

        return $this->workspaces->contains($workspace);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->isAdmin();
    }

    /**
     * @return BelongsTo<Workspace, $this>
     */
    public function currentWorkspace(): BelongsTo
    {
        if ($this->current_workspace_id === null && ($workspace = $this->workspaces->first()) !== null) {
            $this->switchWorkspace($workspace);
        }

        return $this->belongsTo(Workspace::class, 'current_workspace_id');
    }

    public function isAdmin(): bool
    {
        return in_array($this->email, Config::array('admin.emails'), true);
    }

    public function isCurrentWorkspace(Workspace $workspace): bool
    {
        if ($this->currentWorkspace === null) {
            return false;
        }

        return $this->currentWorkspace->id === $workspace->id;
    }

    public function ownsWorkspace(null|Workspace $workspace): bool
    {
        if (! $workspace instanceof Workspace) {
            return false;
        }

        return $this->id === $workspace->owner_id;
    }

    public function switchWorkspace(Workspace $workspace): bool
    {
        if (! $this->belongsToWorkspace($workspace)) {
            return false;
        }

        $this->update([
            'current_workspace_id' => $workspace->id,
        ]);

        $this->setRelation('currentWorkspace', $workspace);

        return true;
    }

    #[Override]
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'name' => PersonNameCast::class,
            'password' => 'hashed',
        ];
    }
}
```

Keep hidden auth fields declared with `#[Hidden(...)]`, use `hashed` casts for passwords, put panel access behind a small config-backed predicate, and cover current-`Workspace` persisted state transitions in `tests/Integration/Models`.

## Related References

- [`../README.md`](../README.md)
