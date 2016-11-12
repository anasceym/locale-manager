<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProjectsRouteTest extends TestCase
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
     * @test
     */
    public function it_should_display_all_projects() {

        $user = factory(App\User::class)->create();

        $projects = factory(App\Project::class, 5)->create([
            'user_id' => $user->id
        ]);

        $this->be($user);

        $this->visitRoute('projects.index');

        $this->assertResponseOk();
    }

    /**
     * @test
     */
    public function it_should_display_project_update_page () {

        $user = factory(App\User::class)->create();

        $project = factory(App\Project::class)->create([
            'user_id' => $user->id
        ]);

        $this->be($user);

        $response = $this->visitRoute('projects.edit', [ 'project' => $project->id ]);

        $this->assertViewHas('project');
        $this->assertResponseOk();;
    }

    /**
     * @test
     */
    public function it_should_return_404_when_unauthorized_to_update_specific_project() {

        $user = factory(App\User::class)->create();

        $project = factory(App\Project::class)->create([
            'user_id' => $user->id
        ]);

        $anotherUser = factory(App\User::class)->create();

        $this->be($anotherUser);

        $this->route('GET', 'projects.edit', [ 'project' => $project->id ]);

        $this->assertResponseStatus(404);
    }

    /**
     * @test
     */
    public function it_should_display_project_show_page() {

        $user = factory(App\User::class)->create();

        $project = factory(App\Project::class)->create([
            'user_id' => $user->id
        ]);

        $this->be($user);

        $this->visitRoute('projects.show', [ 'project' => $project->id ]);
    }

    /**
     * @test
     */
    public function it_should_return_404_when_unauthorized_to_show_specific_project() {

        $user = factory(App\User::class)->create();

        $project = factory(App\Project::class)->create([
            'user_id' => $user->id
        ]);

        $anotherUser = factory(App\User::class)->create();

        $this->be($anotherUser);

        $this->route('GET', 'projects.show', [ 'project' => $project->id ]);

        $this->assertResponseStatus(404);
    }
}
