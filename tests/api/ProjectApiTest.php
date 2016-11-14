<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProjectApiTest extends TestCase
{

    use DatabaseMigrations;

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
    public function it_should_give_all_projects_for_the_request_user() {

        $project = factory(App\Project::class)->create();

        $this->be($project->owner, 'api');

        $request = $this->json('get', '/api/projects');

        $this->assertResponseStatus(200);
        $request->seeJsonStructure($this->getPaginatedItemsExpectedStructure($this->getExpectedProjectStructure()));
    }

    /**
     * @test
     */
    public function it_should_see_new_creation_of_project() {

        $postData = [
            'name' => 'Kiddos'
        ];

        $this->be(factory(App\User::class)->create(), 'api');

        $request = $this->json('post', '/api/projects', $postData);

        $this->assertResponseStatus(201);
        $request->seeJsonStructure($this->getExpectedProjectStructure());
    }

    /**
     * @test
     */
    public function it_should_failed_when_no_post_data_is_sent() {

        $this->be(factory(App\User::class)->create(), 'api');

        $request = $this->json('post', '/api/projects');

        $this->assertResponseStatus(422);

        $request->seeJsonStructure($this->getErrorExpectedStructure());
    }

    /**
     * @test
     */
    public function it_should_show_particular_project() {

        $project = factory(App\Project::class)->create();

        $this->be($project->owner, 'api');

        $request = $this->json('get', "/api/projects/{$project->id}");

        $this->assertResponseStatus(200);
        $request->seeJsonStructure($this->getExpectedProjectStructure());
    }

    /**
     * @test
     */
    public function it_should_return_404_when_unauthorized() {

        $project = factory(App\Project::class)->create();

        $differentUser = factory(App\User::class)->create();

        $this->be($differentUser, 'api');

        $request = $this->json('get', "/api/projects/{$project->id}");

        $this->assertResponseStatus(404);
    }

    /**
     * @test
     */
    public function it_should_delete_particular_project() {

        $project = factory(App\Project::class)->create();

        $this->be($project->owner, 'api');

        $request = $this->json('delete', "/api/projects/{$project->id}");

        $this->assertResponseStatus(204);
        $this->dontSeeInDatabase('projects', ['id' => $project->id, 'deleted_at' => null]);
    }

    /**
     * @test
     */
    public function it_should_return_404_when_unauthorized_to_delete() {

        $project = factory(App\Project::class)->create();

        $differentUser = factory(App\User::class)->create();

        $this->be($differentUser, 'api');

        $request = $this->json('delete', "/api/projects/{$project->id}");

        $this->assertResponseStatus(404);
        $this->assertNotNull(\App\Project::find($project->id));
    }

    /**
     * @test
     */
    public function it_should_update_particular_project() {

        $project = factory(App\Project::class)->create();

        $updatePostData = [
            'name' => 'Kiddos New Project'
        ];

        $this->be($project->owner, 'api');

        $request = $this->json('patch', "/api/projects/{$project->id}", $updatePostData);

        $this->assertResponseStatus(200);

        $this->seeInDatabase('projects', [
            'id' => $project->id,
            'name' => $updatePostData['name']
        ]);
    }

    /**
     * @test
     */
    public function it_should_return_404_when_unauthorized_to_update() {

        $project = factory(App\Project::class)->create();

        $differentUser = factory(App\User::class)->create();

        $this->be($differentUser, 'api');

        $request = $this->json('patch', "/api/projects/{$project->id}");

        $this->assertResponseStatus(404);
    }

    private function getExpectedProjectStructure() {

        return [
            'id',
            'name'
        ];
    }
}
