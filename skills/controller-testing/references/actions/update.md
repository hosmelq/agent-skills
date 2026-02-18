# Update Action Templates

Use these as complete `describe('update')` templates, including baseline `validates fields` dataset examples.

Actor context for `login()` in these templates:

- `assertForbidden()` tests: use an authenticated **in-scope** actor who can resolve bindings but lacks permission for the action.
- `validates fields` and success-path tests: use an authenticated **authorized in-scope** actor.
- `assertNotFound()` binding-mismatch tests: any authenticated actor is fine because binding fails before authorization.

## One-Level Nested (`organizations.projects.update`)

```php
describe('update', function (): void {
    it('requires authentication', function (): void {
        $project = Project::factory()->createOne();

        $response = patch(route('organizations.projects.update', [
            'organization' => $project->organization,
            'project' => $project,
        ]));

        $response->assertRedirectToRoute('login');
    });

    it('prevents updating from an unrelated organization', function (): void {
        $unrelatedProject = Project::factory()->createOne();

        login();

        $response = patch(route('organizations.projects.update', [
            'organization' => $unrelatedProject->organization,
            'project' => $unrelatedProject,
        ]));

        $response->assertForbidden();
    });

    it('returns not found when project belongs to different organization', function (): void {
        $relatedOrganization = Organization::factory()->createOne();

        $unrelatedProject = Project::factory()->createOne();

        login(organization: $relatedOrganization);

        $response = patch(route('organizations.projects.update', [
            'organization' => $relatedOrganization,
            'project' => $unrelatedProject,
        ]));

        $response->assertNotFound();
    });

    it('validates fields', function (array $data, array $expected): void {
        $project = Project::factory()->createOne();

        login(organization: $project->organization);

        $response = patch(route('organizations.projects.update', [
            'organization' => $project->organization,
            'project' => $project,
        ]), $data);

        $response->assertRedirectBackWithErrors($expected);
    })->with([
        'gte:0' => [
            'data' => [
                'budget' => -2,
            ],
            'expected' => [
                'budget' => 'The budget field must be greater than or equal to 0.',
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
        'sometimes (required)' => [
            'data' => [
                'name' => '',
            ],
            'expected' => [
                'name' => 'The name field is required.',
            ],
        ],
    ]);

    it('updates a project', function (): void {
        $project = Project::factory()->createOne();

        login(organization: $project->organization);

        $response = patch(route('organizations.projects.update', [
            'organization' => $project->organization,
            'project' => $project,
        ]), [
            'name' => 'Renamed Project',
            'description' => 'Updated details',
            'budget' => 3000,
        ]);

        $response->assertRedirectToRoute('organizations.projects.show', [
            'organization' => $project->organization,
            'project' => $project,
        ])
            ->assertToast('Project updated', 'The project was updated successfully.');

        $project->refresh();

        expect($project)
            ->name->toBe('Renamed Project')
            ->description->toBe('Updated details')
            ->budget->toBe('3000.00');
    });
});
```

## Two-Level Nested (`organizations.projects.tasks.update`)

```php
describe('update', function (): void {
    it('requires authentication', function (): void {
        $task = Task::factory()->createOne();

        $response = patch(route('organizations.projects.tasks.update', [
            'organization' => $task->project->organization,
            'project' => $task->project,
            'task' => $task,
        ]));

        $response->assertRedirectToRoute('login');
    });

    it('prevents updating from an unrelated organization', function (): void {
        $unrelatedTask = Task::factory()->createOne();

        login();

        $response = patch(route('organizations.projects.tasks.update', [
            'organization' => $unrelatedTask->project->organization,
            'project' => $unrelatedTask->project,
            'task' => $unrelatedTask,
        ]));

        $response->assertForbidden();
    });

    it('returns not found when project belongs to different organization', function (): void {
        $task = Task::factory()->createOne();

        $unrelatedProject = Project::factory()->createOne();

        login(organization: $task->project->organization);

        $response = patch(route('organizations.projects.tasks.update', [
            'organization' => $task->project->organization,
            'project' => $unrelatedProject,
            'task' => $task,
        ]));

        $response->assertNotFound();
    });

    it('returns not found when task belongs to different project', function (): void {
        $project = Project::factory()->createOne();

        $unrelatedTask = Task::factory()
            ->for(Project::factory()->for($project->organization)->createOne())
            ->createOne();

        login(organization: $project->organization);

        $response = patch(route('organizations.projects.tasks.update', [
            'organization' => $project->organization,
            'project' => $project,
            'task' => $unrelatedTask,
        ]));

        $response->assertNotFound();
    });

    it('validates fields', function (array $data, array $expected): void {
        $task = Task::factory()->createOne();

        login(organization: $task->project->organization);

        $response = patch(route('organizations.projects.tasks.update', [
            'organization' => $task->project->organization,
            'project' => $task->project,
            'task' => $task,
        ]), $data);

        $response->assertRedirectBackWithErrors($expected);
    })->with([
        'decimal:0,2' => [
            'data' => [
                'estimate_hours' => 2.123,
            ],
            'expected' => [
                'estimate_hours' => 'The estimate hours field must have 0-2 decimal places.',
            ],
        ],
        'gte:0' => [
            'data' => [
                'estimate_hours' => -3,
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
        'sometimes (required)' => [
            'data' => [
                'title' => '',
            ],
            'expected' => [
                'title' => 'The title field is required.',
            ],
        ],
    ]);

    it('updates a task', function (): void {
        $task = Task::factory()->createOne();

        login(organization: $task->project->organization);

        $response = patch(route('organizations.projects.tasks.update', [
            'organization' => $task->project->organization,
            'project' => $task->project,
            'task' => $task,
        ]), [
            'title' => 'Refactor policy checks',
            'estimate_hours' => 5,
            'status' => 'in_progress',
        ]);

        $response->assertRedirectToRoute('organizations.projects.tasks.show', [
            'organization' => $task->project->organization,
            'project' => $task->project,
            'task' => $task,
        ])
            ->assertToast('Task updated', 'The task was updated successfully.');

        $task->refresh();

        expect($task)
            ->title->toBe('Refactor policy checks')
            ->estimate_hours->toBe('5.00')
            ->status->toBe('in_progress');
    });
});
```

## Optional Stored-Bound Validation Cases

Add these only when update rules compare incoming values against stored model state.

```php
it('validates minimum against stored maximum', function (): void {
    $record = Record::factory()->createOne([
        'minimum_value' => 5,
        'maximum_value' => 10,
    ]);

    login(organization: $record->organization);

    $response = patch(route('organizations.records.update', [
        'organization' => $record->organization,
        'record' => $record,
    ]), [
        'minimum_value' => 11,
    ]);

    $response->assertRedirectBackWithErrors([
        'minimum_value' => 'The minimum value field must be less than or equal to 10.',
    ]);
});
```

## Additional `validates fields` Snippets

For extra `update` validation rule coverage, prefer focused references first when they match your rules:

- `references/validation/required-with-and-array.md`
- `references/validation/scoped-exists-and-unique.md`
- `references/validation/prepare-for-validation.md`

Then load the broader catalog only for additional rules you still need:

- `references/validation/update-validates-fields.md`
