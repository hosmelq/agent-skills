# Show Action Templates

Use these as complete `describe('show')` templates.

Actor context for `login()` in these templates:

- `assertForbidden()` tests: use an authenticated **in-scope** actor who can resolve bindings but lacks permission for the action.
- Success-path tests: use an authenticated **authorized in-scope** actor.
- `assertNotFound()` binding-mismatch tests: any authenticated actor is fine because binding fails before authorization.

## One-Level Nested (`organizations.projects.show`)

```php
describe('show', function (): void {
    it('requires authentication', function (): void {
        $project = Project::factory()->createOne();

        $response = get(route('organizations.projects.show', [
            'organization' => $project->organization,
            'project' => $project,
        ]));

        $response->assertRedirectToRoute('login');
    });

    it('prevents viewing from an unrelated organization', function (): void {
        $unrelatedProject = Project::factory()->createOne();

        login();

        $response = get(route('organizations.projects.show', [
            'organization' => $unrelatedProject->organization,
            'project' => $unrelatedProject,
        ]));

        $response->assertForbidden();
    });

    it('returns not found when project belongs to different organization', function (): void {
        $relatedOrganization = Organization::factory()->createOne();

        $unrelatedProject = Project::factory()->createOne();

        login(organization: $relatedOrganization);

        $response = get(route('organizations.projects.show', [
            'organization' => $relatedOrganization,
            'project' => $unrelatedProject,
        ]));

        $response->assertNotFound();
    });

    it('shows a project', function (): void {
        $project = Project::factory()->createOne();

        login(organization: $project->organization);

        $response = get(route('organizations.projects.show', [
            'organization' => $project->organization,
            'project' => $project,
        ]));

        $response->assertOk()
            ->assertInertia(function (AssertableInertia $page) use ($project): void {
                $page->component('projects/Show')
                    ->where('project.id', $project->public_id);
            });
    });
});
```

## Two-Level Nested (`organizations.projects.tasks.show`)

```php
describe('show', function (): void {
    it('requires authentication', function (): void {
        $task = Task::factory()->createOne();

        $response = get(route('organizations.projects.tasks.show', [
            'organization' => $task->project->organization,
            'project' => $task->project,
            'task' => $task,
        ]));

        $response->assertRedirectToRoute('login');
    });

    it('prevents viewing from an unrelated organization', function (): void {
        $unrelatedTask = Task::factory()->createOne();

        login();

        $response = get(route('organizations.projects.tasks.show', [
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

        $response = get(route('organizations.projects.tasks.show', [
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

        $response = get(route('organizations.projects.tasks.show', [
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

        $response = get(route('organizations.projects.tasks.show', [
            'organization' => $project->organization,
            'project' => $project,
            'task' => $unrelatedTask,
        ]));

        $response->assertNotFound();
    });

    it('shows a task', function (): void {
        $task = Task::factory()->createOne();

        login(organization: $task->project->organization);

        $response = get(route('organizations.projects.tasks.show', [
            'organization' => $task->project->organization,
            'project' => $task->project,
            'task' => $task,
        ]));

        $response->assertOk()
            ->assertInertia(function (AssertableInertia $page) use ($task): void {
                $page->component('projects/tasks/Show')
                    ->where('project.id', $task->project->public_id)
                    ->where('task.id', $task->public_id);
            });
    });
});
```

## Three-Level Nested (`organizations.projects.tasks.comments.show`)

```php
describe('show', function (): void {
    it('requires authentication', function (): void {
        $comment = Comment::factory()->createOne();

        $response = get(route('organizations.projects.tasks.comments.show', [
            'organization' => $comment->task->project->organization,
            'project' => $comment->task->project,
            'task' => $comment->task,
            'comment' => $comment,
        ]));

        $response->assertRedirectToRoute('login');
    });

    it('prevents viewing from an unrelated organization', function (): void {
        $unrelatedComment = Comment::factory()->createOne();

        login();

        $response = get(route('organizations.projects.tasks.comments.show', [
            'organization' => $unrelatedComment->task->project->organization,
            'project' => $unrelatedComment->task->project,
            'task' => $unrelatedComment->task,
            'comment' => $unrelatedComment,
        ]));

        $response->assertForbidden();
    });

    it('returns not found when project belongs to different organization', function (): void {
        $comment = Comment::factory()->createOne();

        $unrelatedProject = Project::factory()->createOne();

        login(organization: $comment->task->project->organization);

        $response = get(route('organizations.projects.tasks.comments.show', [
            'organization' => $comment->task->project->organization,
            'project' => $unrelatedProject,
            'task' => $comment->task,
            'comment' => $comment,
        ]));

        $response->assertNotFound();
    });

    it('returns not found when task belongs to different project', function (): void {
        $comment = Comment::factory()->createOne();

        $unrelatedTask = Task::factory()
            ->for(Project::factory()->for($comment->task->project->organization)->createOne())
            ->createOne();

        login(organization: $comment->task->project->organization);

        $response = get(route('organizations.projects.tasks.comments.show', [
            'organization' => $comment->task->project->organization,
            'project' => $comment->task->project,
            'task' => $unrelatedTask,
            'comment' => $comment,
        ]));

        $response->assertNotFound();
    });

    it('returns not found when comment belongs to different task', function (): void {
        $task = Task::factory()->createOne();

        $unrelatedComment = Comment::factory()
            ->for(Task::factory()->for($task->project)->createOne())
            ->createOne();

        login(organization: $task->project->organization);

        $response = get(route('organizations.projects.tasks.comments.show', [
            'organization' => $task->project->organization,
            'project' => $task->project,
            'task' => $task,
            'comment' => $unrelatedComment,
        ]));

        $response->assertNotFound();
    });

    it('shows a comment', function (): void {
        $comment = Comment::factory()->createOne();

        login(organization: $comment->task->project->organization);

        $response = get(route('organizations.projects.tasks.comments.show', [
            'organization' => $comment->task->project->organization,
            'project' => $comment->task->project,
            'task' => $comment->task,
            'comment' => $comment,
        ]));

        $response->assertOk()
            ->assertInertia(function (AssertableInertia $page) use ($comment): void {
                $page->component('projects/tasks/comments/Show')
                    ->where('task.id', $comment->task->public_id)
                    ->where('comment.id', $comment->public_id);
            });
    });
});
```
