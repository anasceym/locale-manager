<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProjectsNamespacesRouteTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    /**
     * Test for projects.namespaces.show
     *
     * @test
     */
    public function it_should_show_specific_project_namespace_page() {

        $projectNamespace = factory(App\Project_namespace::class)->create();

        $project = $projectNamespace->project;

        $owner = $project->owner;

        $this->be($owner);

        $response = $this->visit("/projects/{$project->id}/namespaces/{$projectNamespace->id}");

        $this->assertResponseStatus(200);
        $this->assertViewHas('namespace');
        $this->assertViewHas('project');
    }

    /**
     * Test for projects.namespaces.show
     *
     * @test
     */
    public function it_should_return_404_if_namespace_not_belong_to_the_project() {

        $projectNamespace = factory(App\Project_namespace::class)->create();

        $project = $projectNamespace->project;

        $owner = $project->owner;

        $anotherProject = factory(App\Project::class)->create();

        $this->be($owner);

        $response = $this->get("/projects/{$anotherProject->id}/namespaces/{$projectNamespace->id}");

        $this->assertResponseStatus(404);
    }

    /**
     * Test for projects.namespaces.show
     *
     * @test
     */
    public function it_should_return_404_if_the_project_not_belong_to_user() {

        $projectNamespace = factory(App\Project_namespace::class)->create();

        $project = $projectNamespace->project;

        $anotherUser = factory(App\User::class)->create();

        $this->be($anotherUser);

        $response = $this->get("/projects/{$project->id}/namespaces/{$projectNamespace->id}");

        $this->assertResponseStatus(404);
    }
}
