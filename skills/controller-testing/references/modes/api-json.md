# API/JSON Mode for Controller Tests

Use this when the controller test flow is API/JSON instead of web/session.

Actor context for `login()` in these templates:

- `assertForbidden()` tests: use an authenticated **in-scope** actor who can resolve bindings but lacks permission for the action.
- `validates fields` and success-path tests: use an authenticated **authorized in-scope** actor.
- `assertNotFound()` binding-mismatch tests: any authenticated actor is fine because binding fails before authorization.

Canonical status rule:

- Policy denial => `assertForbidden()` (`403`).
- Binding mismatch in route-model resolution => `assertNotFound()` (`404`).

For JSON validation assertions, prefer asserting keys for stability (for example `['email']`), and assert full messages only when your project standardizes message text in tests.

## Request and Assertion Mapping

Keep the same action structure and parent-scope coverage, but swap transport and response assertions:

| Web/Session | API/JSON |
|---|---|
| `get`, `post`, `patch`, `delete` | `getJson`, `postJson`, `patchJson`, `deleteJson` |
| Protected endpoint unauthenticated assertion (`assertRedirectToRoute('login')`) | `assertUnauthorized()` |
| `assertRedirectBackWithErrors(...)` | `assertUnprocessable()->assertJsonValidationErrors(...)` |
| `assertOk() + assertInertia(...)` | `assertOk()/assertCreated()/assertNoContent() + assertJson(...)` |
| redirect + toast assertions | JSON payload/status assertions |

## Protected vs Public API Endpoints

- Protected endpoint:
  add `requires authentication` and assert `assertUnauthorized()`.
- Public endpoint:
  skip auth-required tests unless the endpoint is protected.
  Focus on validation, success payload, and persistence side-effects.

If you are testing API email request/login validation flows, load:
`references/validation/api-login-validation.md`.

## Public Endpoint Template (No Auth Test)

```php
describe('store', function (): void {
    it('validates fields', function (array $data, array $expected): void {
        $response = postJson(route('api.auth.email.request'), $data);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors($expected);
    })->with([
        'email' => [
            'data' => [
                'email' => 'invalid',
            ],
            'expected' => [
                'email' => 'The email field must be a valid email address.',
            ],
        ],
    ]);

    it('returns success payload', function (): void {
        $response = postJson(route('api.auth.email.request'), [
            'email' => 'john@example.com',
        ]);

        $response->assertOk()
            ->assertJson(function (AssertableJson $json): void {
                $json
                    ->where('status', 'ok')
                    ->etc();
            });
    });
});
```

## Full `store` Example (`organizations.projects.store`)

```php
describe('store', function (): void {
    it('requires authentication', function (): void {
        $organization = Organization::factory()->createOne();

        $response = postJson(route('organizations.projects.store', [
            'organization' => $organization,
        ]));

        $response->assertUnauthorized();
    });

    it('prevents creating from an unrelated organization', function (): void {
        $unrelatedOrganization = Organization::factory()->createOne();

        login();

        $response = postJson(route('organizations.projects.store', [
            'organization' => $unrelatedOrganization,
        ]));

        $response->assertForbidden();
    });

    it('validates fields', function (array $data, array $expected): void {
        $organization = Organization::factory()->createOne();

        login(organization: $organization);

        $response = postJson(route('organizations.projects.store', [
            'organization' => $organization,
        ]), $data);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors($expected);
    })->with([
        'max:255' => [
            'data' => [
                'name' => Str::repeat('a', 256),
            ],
            'expected' => [
                'name' => 'The name field must not be greater than 255 characters.',
            ],
        ],
        'required' => [
            'data' => [],
            'expected' => [
                'name' => 'The name field is required.',
                'budget' => 'The budget field is required.',
            ],
        ],
    ]);

    it('creates a project', function (): void {
        $organization = Organization::factory()->createOne();

        login(organization: $organization);

        $response = postJson(route('organizations.projects.store', [
            'organization' => $organization,
        ]), [
            'name' => 'New Project',
            'description' => 'Project details',
            'budget' => 1500,
        ]);

        $response->assertCreated()
            ->assertJson(function (AssertableJson $json): void {
                $json
                    ->has('data.id')
                    ->where('data.name', 'New Project')
                    ->etc();
            });

        assertDatabaseHas(Project::class, [
            'organization_id' => $organization->id,
            'name' => 'New Project',
            'description' => 'Project details',
            'budget' => '1500.00',
        ]);
    });
});
```

## Full `update` Example (`organizations.projects.update`)

```php
describe('update', function (): void {
    it('requires authentication', function (): void {
        $project = Project::factory()->createOne();

        $response = patchJson(route('organizations.projects.update', [
            'organization' => $project->organization,
            'project' => $project,
        ]));

        $response->assertUnauthorized();
    });

    it('prevents updating from an unrelated organization', function (): void {
        $unrelatedProject = Project::factory()->createOne();

        login();

        $response = patchJson(route('organizations.projects.update', [
            'organization' => $unrelatedProject->organization,
            'project' => $unrelatedProject,
        ]));

        $response->assertForbidden();
    });

    it('returns not found when project belongs to different organization', function (): void {
        $relatedOrganization = Organization::factory()->createOne();

        $unrelatedProject = Project::factory()->createOne();

        login(organization: $relatedOrganization);

        $response = patchJson(route('organizations.projects.update', [
            'organization' => $relatedOrganization,
            'project' => $unrelatedProject,
        ]), [
            'name' => 'Renamed Project',
        ]);

        $response->assertNotFound();
    });

    it('validates fields', function (array $data, array $expected): void {
        $project = Project::factory()->createOne();

        login(organization: $project->organization);

        $response = patchJson(route('organizations.projects.update', [
            'organization' => $project->organization,
            'project' => $project,
        ]), $data);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors($expected);
    })->with([
        'max:255' => [
            'data' => [
                'name' => Str::repeat('a', 256),
            ],
            'expected' => [
                'name' => 'The name field must not be greater than 255 characters.',
            ],
        ],
        'numeric' => [
            'data' => [
                'budget' => 'invalid',
            ],
            'expected' => [
                'budget' => 'The budget field must be a number.',
            ],
        ],
    ]);

    it('updates a project', function (): void {
        $project = Project::factory()->createOne();

        login(organization: $project->organization);

        $response = patchJson(route('organizations.projects.update', [
            'organization' => $project->organization,
            'project' => $project,
        ]), [
            'name' => 'Renamed Project',
            'description' => 'Updated details',
            'budget' => 3000,
        ]);

        $response->assertOk()
            ->assertJsonPath('data.name', 'Renamed Project');

        $project->refresh();

        expect($project)
            ->name->toBe('Renamed Project')
            ->description->toBe('Updated details')
            ->budget->toBe('3000.00');
    });
});
```
