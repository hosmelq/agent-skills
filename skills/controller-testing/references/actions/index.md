# Index Action Templates

Use these as complete `describe('index')` templates.

Actor context for `login()` in these templates:

- `assertForbidden()` tests: use an authenticated **in-scope** actor who can resolve bindings but lacks permission for the action.
- List/success assertions: use an authenticated **authorized in-scope** actor.
- `assertNotFound()` binding-mismatch tests: any authenticated actor is fine because binding fails before authorization.

## One-Level Nested (`organizations.projects.index`)

```php
describe('index', function (): void {
    it('requires authentication', function (): void {
        $organization = Organization::factory()->createOne();

        $response = get(route('organizations.projects.index', [
            'organization' => $organization,
        ]));

        $response->assertRedirectToRoute('login');
    });

    it('prevents listing from an unrelated organization', function (): void {
        $unrelatedOrganization = Organization::factory()->createOne();

        login();

        $response = get(route('organizations.projects.index', [
            'organization' => $unrelatedOrganization,
        ]));

        $response->assertForbidden();
    });

    it('lists projects', function (): void {
        $project = Project::factory()->createOne();

        login(organization: $project->organization);

        $response = get(route('organizations.projects.index', [
            'organization' => $project->organization,
        ]));

        $response->assertOk()
            ->assertInertia(function (AssertableInertia $page) use ($project): void {
                $page->component('projects/Index')
                    ->where('projects.data.0.id', $project->public_id);
            });
    });

    it('does not include projects from other organizations', function (): void {
        $project = Project::factory()->createOne();

        $unrelatedProject = Project::factory()->createOne();

        login(organization: $project->organization);

        $response = get(route('organizations.projects.index', [
            'organization' => $project->organization,
        ]));

        $response->assertOk()
            ->assertInertia(function (AssertableInertia $page) use ($project, $unrelatedProject): void {
                $page->component('projects/Index')
                    ->has('projects.data', 1, function (AssertableJson $json) use ($project, $unrelatedProject): void {
                        $json
                            ->where('id', $project->public_id)
                            ->whereNot('id', $unrelatedProject->public_id)
                            ->etc();
                    });
            });
    });
});
```

## Two-Level Nested (`organizations.projects.tasks.index`)

```php
describe('index', function (): void {
    it('requires authentication', function (): void {
        $project = Project::factory()->createOne();

        $response = get(route('organizations.projects.tasks.index', [
            'organization' => $project->organization,
            'project' => $project,
        ]));

        $response->assertRedirectToRoute('login');
    });

    it('prevents listing from an unrelated organization', function (): void {
        $unrelatedTask = Task::factory()->createOne();

        login();

        $response = get(route('organizations.projects.tasks.index', [
            'organization' => $unrelatedTask->project->organization,
            'project' => $unrelatedTask->project,
        ]));

        $response->assertForbidden();
    });

    it('returns not found when project belongs to different organization', function (): void {
        $relatedOrganization = Organization::factory()->createOne();

        $unrelatedProject = Project::factory()->createOne();

        login(organization: $relatedOrganization);

        $response = get(route('organizations.projects.tasks.index', [
            'organization' => $relatedOrganization,
            'project' => $unrelatedProject,
        ]));

        $response->assertNotFound();
    });

    it('lists tasks', function (): void {
        $task = Task::factory()->createOne();

        login(organization: $task->project->organization);

        $response = get(route('organizations.projects.tasks.index', [
            'organization' => $task->project->organization,
            'project' => $task->project,
        ]));

        $response->assertOk()
            ->assertInertia(function (AssertableInertia $page) use ($task): void {
                $page->component('projects/tasks/Index')
                    ->where('project.id', $task->project->public_id)
                    ->where('tasks.data.0.id', $task->public_id);
            });
    });

    it('does not include tasks from other organizations', function (): void {
        $task = Task::factory()->createOne();

        $unrelatedTask = Task::factory()->createOne();

        login(organization: $task->project->organization);

        $response = get(route('organizations.projects.tasks.index', [
            'organization' => $task->project->organization,
            'project' => $task->project,
        ]));

        $response->assertOk()
            ->assertInertia(function (AssertableInertia $page) use ($task, $unrelatedTask): void {
                $page->component('projects/tasks/Index')
                    ->has('tasks.data', 1, function (AssertableJson $json) use ($task, $unrelatedTask): void {
                        $json
                            ->where('id', $task->public_id)
                            ->whereNot('id', $unrelatedTask->public_id)
                            ->etc();
                    });
            });
    });

    it('does not include tasks from other projects', function (): void {
        $task = Task::factory()->createOne();

        $unrelatedTask = Task::factory()
            ->for(Project::factory()->for($task->project->organization)->createOne())
            ->createOne();

        login(organization: $task->project->organization);

        $response = get(route('organizations.projects.tasks.index', [
            'organization' => $task->project->organization,
            'project' => $task->project,
        ]));

        $response->assertOk()
            ->assertInertia(function (AssertableInertia $page) use ($task, $unrelatedTask): void {
                $page->component('projects/tasks/Index')
                    ->has('tasks.data', 1, function (AssertableJson $json) use ($task, $unrelatedTask): void {
                        $json
                            ->where('id', $task->public_id)
                            ->whereNot('id', $unrelatedTask->public_id)
                            ->etc();
                    });
            });
    });
});
```
