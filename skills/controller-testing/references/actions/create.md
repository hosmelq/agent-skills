# Create Action Templates

Use these as complete `describe('create')` templates.

Actor context for `login()` in these templates:

- `assertForbidden()` tests: use an authenticated **in-scope** actor who can resolve bindings but lacks permission for the action.
- Success-path tests: use an authenticated **authorized in-scope** actor.
- `assertNotFound()` binding-mismatch tests: any authenticated actor is fine because binding fails before authorization.

## One-Level Nested (`organizations.projects.create`)

```php
describe('create', function (): void {
    it('requires authentication', function (): void {
        $organization = Organization::factory()->createOne();

        $response = get(route('organizations.projects.create', [
            'organization' => $organization,
        ]));

        $response->assertRedirectToRoute('login');
    });

    it('prevents viewing from an unrelated organization', function (): void {
        $unrelatedOrganization = Organization::factory()->createOne();

        login();

        $response = get(route('organizations.projects.create', [
            'organization' => $unrelatedOrganization,
        ]));

        $response->assertForbidden();
    });

    it('shows the create project page', function (): void {
        $organization = Organization::factory()->createOne();

        login(organization: $organization);

        $response = get(route('organizations.projects.create', [
            'organization' => $organization,
        ]));

        $response->assertOk()
            ->assertInertia(function (AssertableInertia $page): void {
                $page->component('projects/Create');
            });
    });
});
```

## Two-Level Nested (`organizations.projects.tasks.create`)

```php
describe('create', function (): void {
    it('requires authentication', function (): void {
        $project = Project::factory()->createOne();

        $response = get(route('organizations.projects.tasks.create', [
            'organization' => $project->organization,
            'project' => $project,
        ]));

        $response->assertRedirectToRoute('login');
    });

    it('prevents viewing from an unrelated organization', function (): void {
        $unrelatedTask = Task::factory()->createOne();

        login();

        $response = get(route('organizations.projects.tasks.create', [
            'organization' => $unrelatedTask->project->organization,
            'project' => $unrelatedTask->project,
        ]));

        $response->assertForbidden();
    });

    it('returns not found when project belongs to different organization', function (): void {
        $relatedOrganization = Organization::factory()->createOne();

        $unrelatedProject = Project::factory()->createOne();

        login(organization: $relatedOrganization);

        $response = get(route('organizations.projects.tasks.create', [
            'organization' => $relatedOrganization,
            'project' => $unrelatedProject,
        ]));

        $response->assertNotFound();
    });

    it('shows the create task page', function (): void {
        $project = Project::factory()->createOne();

        login(organization: $project->organization);

        $response = get(route('organizations.projects.tasks.create', [
            'organization' => $project->organization,
            'project' => $project,
        ]));

        $response->assertOk()
            ->assertInertia(function (AssertableInertia $page) use ($project): void {
                $page->component('projects/tasks/Create')
                    ->where('project.id', $project->public_id);
            });
    });
});
```

## Optional Inertia Partial Reload Assertion

Use this when the page supports partial reload props.

```php
$response->assertOk()
    ->assertInertia(function (AssertableInertia $page) use ($organization): void {
        $page->component('projects/Create')
            ->where('organization.id', $organization->public_id)
            ->reloadOnly('currencies', function (AssertableInertia $reload): void {
                $reload->has('currencies');
            });
    });
```
