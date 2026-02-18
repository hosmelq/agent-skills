# Edit Action Templates

Use these as complete `describe('edit')` templates.

Actor context for `login()` in these templates:

- `assertForbidden()` tests: use an authenticated **in-scope** actor who can resolve bindings but lacks permission for the action.
- Success-path tests: use an authenticated **authorized in-scope** actor.
- `assertNotFound()` binding-mismatch tests: any authenticated actor is fine because binding fails before authorization.

## One-Level Nested (`organizations.projects.edit`)

```php
describe('edit', function (): void {
    it('requires authentication', function (): void {
        $project = Project::factory()->createOne();

        $response = get(route('organizations.projects.edit', [
            'organization' => $project->organization,
            'project' => $project,
        ]));

        $response->assertRedirectToRoute('login');
    });

    it('prevents viewing from an unrelated organization', function (): void {
        $unrelatedProject = Project::factory()->createOne();

        login();

        $response = get(route('organizations.projects.edit', [
            'organization' => $unrelatedProject->organization,
            'project' => $unrelatedProject,
        ]));

        $response->assertForbidden();
    });

    it('returns not found when project belongs to different organization', function (): void {
        $relatedOrganization = Organization::factory()->createOne();

        $unrelatedProject = Project::factory()->createOne();

        login(organization: $relatedOrganization);

        $response = get(route('organizations.projects.edit', [
            'organization' => $relatedOrganization,
            'project' => $unrelatedProject,
        ]));

        $response->assertNotFound();
    });

    it('shows the edit project page', function (): void {
        $project = Project::factory()->createOne();

        login(organization: $project->organization);

        $response = get(route('organizations.projects.edit', [
            'organization' => $project->organization,
            'project' => $project,
        ]));

        $response->assertOk()
            ->assertInertia(function (AssertableInertia $page) use ($project): void {
                $page->component('projects/Edit')
                    ->where('project.id', $project->public_id);
            });
    });
});
```

## Two-Level Nested (`organizations.projects.tasks.edit`)

```php
describe('edit', function (): void {
    it('requires authentication', function (): void {
        $task = Task::factory()->createOne();

        $response = get(route('organizations.projects.tasks.edit', [
            'organization' => $task->project->organization,
            'project' => $task->project,
            'task' => $task,
        ]));

        $response->assertRedirectToRoute('login');
    });

    it('prevents viewing from an unrelated organization', function (): void {
        $unrelatedTask = Task::factory()->createOne();

        login();

        $response = get(route('organizations.projects.tasks.edit', [
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

        $response = get(route('organizations.projects.tasks.edit', [
            'organization' => $task->project->organization,
            'project' => $unrelatedProject,
            'task' => $task,
        ]));

        $response->assertNotFound();
    });

    it('returns not found when task belongs to different organization', function (): void {
        $task = Task::factory()->createOne();

        $unrelatedTask = Task::factory()->createOne();

        login(organization: $task->project->organization);

        $response = get(route('organizations.projects.tasks.edit', [
            'organization' => $task->project->organization,
            'project' => $task->project,
            'task' => $unrelatedTask,
        ]));

        $response->assertNotFound();
    });

    it('returns not found when task belongs to different project', function (): void {
        $project = Project::factory()->createOne();

        $unrelatedTask = Task::factory()
            ->for(Project::factory()->for($project->organization)->createOne())
            ->createOne();

        login(organization: $project->organization);

        $response = get(route('organizations.projects.tasks.edit', [
            'organization' => $project->organization,
            'project' => $project,
            'task' => $unrelatedTask,
        ]));

        $response->assertNotFound();
    });

    it('shows the edit task page', function (): void {
        $task = Task::factory()->createOne();

        login(organization: $task->project->organization);

        $response = get(route('organizations.projects.tasks.edit', [
            'organization' => $task->project->organization,
            'project' => $task->project,
            'task' => $task,
        ]));

        $response->assertOk()
            ->assertInertia(function (AssertableInertia $page) use ($task): void {
                $page->component('projects/tasks/Edit')
                    ->where('project.id', $task->project->public_id)
                    ->where('task.id', $task->public_id);
            });
    });
});
```
