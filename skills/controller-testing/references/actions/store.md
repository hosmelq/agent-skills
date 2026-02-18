# Store Action Templates

Use these as complete `describe('store')` templates, including baseline `validates fields` dataset examples.

Actor context for `login()` in these templates:

- `assertForbidden()` tests: use an authenticated **in-scope** actor who can resolve bindings but lacks permission for the action.
- `validates fields` and success-path tests: use an authenticated **authorized in-scope** actor.
- `assertNotFound()` binding-mismatch tests: any authenticated actor is fine because binding fails before authorization.

## One-Level Nested (`organizations.projects.store`)

```php
describe('store', function (): void {
    it('requires authentication', function (): void {
        $organization = Organization::factory()->createOne();

        $response = post(route('organizations.projects.store', [
            'organization' => $organization,
        ]));

        $response->assertRedirectToRoute('login');
    });

    it('prevents creating from an unrelated organization', function (): void {
        $unrelatedOrganization = Organization::factory()->createOne();

        login();

        $response = post(route('organizations.projects.store', [
            'organization' => $unrelatedOrganization,
        ]));

        $response->assertForbidden();
    });

    it('validates fields', function (array $data, array $expected): void {
        $organization = Organization::factory()->createOne();

        login(organization: $organization);

        $response = post(route('organizations.projects.store', [
            'organization' => $organization,
        ]), $data);

        $response->assertRedirectBackWithErrors($expected);
    })->with([
        'gte:0' => [
            'data' => [
                'budget' => -1,
            ],
            'expected' => [
                'budget' => 'The budget field must be greater than or equal to 0.',
            ],
        ],
        'max:2000' => [
            'data' => [
                'description' => Str::repeat('a', 2001),
            ],
            'expected' => [
                'description' => 'The description field must not be greater than 2000 characters.',
            ],
        ],
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

        $response = post(route('organizations.projects.store', [
            'organization' => $organization,
        ]), [
            'name' => 'New Project',
            'description' => 'Project details',
            'budget' => 1500,
        ]);

        $project = $organization->projects()
            ->where('name', 'New Project')
            ->sole();

        $response->assertRedirectToRoute('organizations.projects.show', [
            'organization' => $organization,
            'project' => $project,
        ])
            ->assertToast('Project created', 'The project was created successfully.');

        assertDatabaseHas(Project::class, [
            'organization_id' => $organization->id,
            'name' => 'New Project',
            'description' => 'Project details',
            'budget' => '1500.00',
        ]);
    });
});
```

## Two-Level Nested (`organizations.projects.tasks.store`)

```php
describe('store', function (): void {
    it('requires authentication', function (): void {
        $project = Project::factory()->createOne();

        $response = post(route('organizations.projects.tasks.store', [
            'organization' => $project->organization,
            'project' => $project,
        ]));

        $response->assertRedirectToRoute('login');
    });

    it('prevents creating from an unrelated organization', function (): void {
        $unrelatedTask = Task::factory()->createOne();

        login();

        $response = post(route('organizations.projects.tasks.store', [
            'organization' => $unrelatedTask->project->organization,
            'project' => $unrelatedTask->project,
        ]));

        $response->assertForbidden();
    });

    it('returns not found when project belongs to different organization', function (): void {
        $relatedOrganization = Organization::factory()->createOne();

        $unrelatedProject = Project::factory()->createOne();

        login(organization: $relatedOrganization);

        $response = post(route('organizations.projects.tasks.store', [
            'organization' => $relatedOrganization,
            'project' => $unrelatedProject,
        ]));

        $response->assertNotFound();
    });

    it('validates fields', function (array $data, array $expected): void {
        $project = Project::factory()->createOne();

        login(organization: $project->organization);

        $response = post(route('organizations.projects.tasks.store', [
            'organization' => $project->organization,
            'project' => $project,
        ]), $data);

        $response->assertRedirectBackWithErrors($expected);
    })->with([
        'decimal:0,2' => [
            'data' => [
                'estimate_hours' => 12.345,
            ],
            'expected' => [
                'estimate_hours' => 'The estimate hours field must have 0-2 decimal places.',
            ],
        ],
        'gte:0' => [
            'data' => [
                'estimate_hours' => -1,
            ],
            'expected' => [
                'estimate_hours' => 'The estimate hours field must be greater than or equal to 0.',
            ],
        ],
        'in' => [
            'data' => [
                'status' => 'invalid',
            ],
            'expected' => [
                'status' => 'The selected status is invalid.',
            ],
        ],
        'max:255' => [
            'data' => [
                'title' => Str::repeat('a', 256),
            ],
            'expected' => [
                'title' => 'The title field must not be greater than 255 characters.',
            ],
        ],
        'required' => [
            'data' => [],
            'expected' => [
                'title' => 'The title field is required.',
                'estimate_hours' => 'The estimate hours field is required.',
                'status' => 'The status field is required.',
            ],
        ],
    ]);

    it('creates a task', function (): void {
        $project = Project::factory()->createOne();

        login(organization: $project->organization);

        $response = post(route('organizations.projects.tasks.store', [
            'organization' => $project->organization,
            'project' => $project,
        ]), [
            'title' => 'Implement API endpoint',
            'estimate_hours' => 3.5,
            'status' => 'todo',
        ]);

        $task = $project->tasks()
            ->where('title', 'Implement API endpoint')
            ->sole();

        $response->assertRedirectToRoute('organizations.projects.tasks.show', [
            'organization' => $project->organization,
            'project' => $project,
            'task' => $task,
        ])
            ->assertToast('Task created', 'The task was created successfully.');

        assertDatabaseHas(Task::class, [
            'project_id' => $project->id,
            'title' => 'Implement API endpoint',
            'estimate_hours' => '3.50',
            'status' => 'todo',
        ]);
    });
});
```

## Additional `validates fields` Snippets

For extra `store` validation rule coverage, prefer focused references first when they match your rules:

- `references/validation/required-with-and-array.md`
- `references/validation/scoped-exists-and-unique.md`
- `references/validation/prepare-for-validation.md`

Then load the broader catalog only for additional rules you still need:

- `references/validation/store-validates-fields.md`
