# Destroy Action Templates

Use these as complete `describe('destroy')` templates.

Actor context for `login()` in these templates:

- `assertForbidden()` tests: use an authenticated **in-scope** actor who can resolve bindings but lacks permission for the action.
- Success-path tests: use an authenticated **authorized in-scope** actor.
- `assertNotFound()` binding-mismatch tests: any authenticated actor is fine because binding fails before authorization.

## One-Level Nested (`organizations.projects.destroy`)

```php
describe('destroy', function (): void {
    it('requires authentication', function (): void {
        $project = Project::factory()->createOne();

        $response = delete(route('organizations.projects.destroy', [
            'organization' => $project->organization,
            'project' => $project,
        ]));

        $response->assertRedirectToRoute('login');
    });

    it('prevents deleting from an unrelated organization', function (): void {
        $unrelatedProject = Project::factory()->createOne();

        login();

        $response = delete(route('organizations.projects.destroy', [
            'organization' => $unrelatedProject->organization,
            'project' => $unrelatedProject,
        ]));

        $response->assertForbidden();
    });

    it('returns not found when project belongs to different organization', function (): void {
        $relatedOrganization = Organization::factory()->createOne();

        $unrelatedProject = Project::factory()->createOne();

        login(organization: $relatedOrganization);

        $response = delete(route('organizations.projects.destroy', [
            'organization' => $relatedOrganization,
            'project' => $unrelatedProject,
        ]));

        $response->assertNotFound();
    });

    it('deletes a project', function (): void {
        $project = Project::factory()->createOne();

        login(organization: $project->organization);

        $response = delete(route('organizations.projects.destroy', [
            'organization' => $project->organization,
            'project' => $project,
        ]));

        $response->assertRedirectToRoute('organizations.projects.index', [
            'organization' => $project->organization,
        ])
            ->assertToast('Project deleted', 'The project was deleted successfully.');

        assertSoftDeleted($project);
    });
});
```

## Two-Level Nested (`organizations.projects.tasks.destroy`)

```php
describe('destroy', function (): void {
    it('requires authentication', function (): void {
        $task = Task::factory()->createOne();

        $response = delete(route('organizations.projects.tasks.destroy', [
            'organization' => $task->project->organization,
            'project' => $task->project,
            'task' => $task,
        ]));

        $response->assertRedirectToRoute('login');
    });

    it('prevents deleting from an unrelated organization', function (): void {
        $unrelatedTask = Task::factory()->createOne();

        login();

        $response = delete(route('organizations.projects.tasks.destroy', [
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

        $response = delete(route('organizations.projects.tasks.destroy', [
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

        $response = delete(route('organizations.projects.tasks.destroy', [
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

        $response = delete(route('organizations.projects.tasks.destroy', [
            'organization' => $project->organization,
            'project' => $project,
            'task' => $unrelatedTask,
        ]));

        $response->assertNotFound();
    });

    it('deletes a task', function (): void {
        $task = Task::factory()->createOne();

        login(organization: $task->project->organization);

        $response = delete(route('organizations.projects.tasks.destroy', [
            'organization' => $task->project->organization,
            'project' => $task->project,
            'task' => $task,
        ]));

        $response->assertRedirectToRoute('organizations.projects.tasks.index', [
            'organization' => $task->project->organization,
            'project' => $task->project,
        ])
            ->assertToast('Task deleted', 'The task was deleted successfully.');

        assertModelMissing($task);
    });
});
```
