<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProjectNamespaceApiTest extends TestCase
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
    public function it_should_add_a_project_namespace() {

        $project = factory(App\Project::class)->create();

        $this->be($project->owner);

        $postData = [
            'name' => 'auth'
        ];

        $expectedJsonStructure = [
            'id',
            'name'
        ];

        $request = $this->json('post', "/api/projects/{$project->id}/namespaces", $postData);

        $this->assertResponseStatus(201);

        $request->seeJsonStructure($expectedJsonStructure);
    }

    /**
     * @test
     */
    public function it_should_return_422_when_name_is_not_specified() {

        $project = factory(App\Project::class)->create();

        $this->be($project->owner);

        $postData = [];

        $request = $this->json('post', "/api/projects/{$project->id}/namespaces", $postData);

        $this->assertResponseStatus(422);

        $request->seeJsonStructure($this->getErrorExpectedStructure());
    }

    /**
     * @test
     */
    public function it_should_return_404_when_unauthorized_to_the_project() {

        $project = factory(App\Project::class)->create();

        $anotherUser = factory(App\User::class)->create();

        $this->be($anotherUser);

        $postData = [
            'name' => 'auth'
        ];

        $request = $this->json('post', "/api/projects/{$project->id}/namespaces", $postData);

        $this->assertResponseStatus(404);
    }

    /**
     * @test
     */
    public function it_should_return_409_when_posting_same_namespace_name() {

        $sameName = 'kiddos';

        $existingNamespace = factory(App\Project_namespace::class)->create(['name' => $sameName]);

        $this->be($existingNamespace->project->owner);

        $postData = [
            'name' => $sameName
        ];

        $request = $this->json('post', "/api/projects/{$existingNamespace->project->id}/namespaces", $postData);

        $this->assertResponseStatus(409);
    }

    /**
     * @test
     */
    public function it_should_delete_a_particular_project_namespace() {

        $namespace = factory(App\Project_namespace::class)->create();

        $this->be($namespace->project->owner);

        $request = $this->json('delete', "/api/projects/{$namespace->project->id}/namespaces/{$namespace->id}");

        $this->assertResponseStatus(204);

        $this->dontSeeInDatabase('project_namespaces', ['id' => $namespace->id, 'deleted_at' => null]);
    }

    /**
     * @test
     */
    public function it_should_return_404_when_namespace_with_different_project_id() {

        $namespace = factory(App\Project_namespace::class)->create();

        $this->be($namespace->project->owner);

        $anotherProject = factory(App\Project::class)->create();

        $request = $this->json('delete', "/api/projects/{$anotherProject->id}/namespaces/{$namespace->id}");

        $this->assertResponseStatus(404);
    }

    /**
     * @test
     */
    public function it_should_return_404_when_unauthorize_to_the_project() {

        $namespace = factory(App\Project_namespace::class)->create();

        $anotherUser = factory(App\User::class)->create();

        $this->be($anotherUser);

        $request = $this->json('delete', "/api/projects/{$namespace->project->id}/namespaces/{$namespace->id}");

        $this->assertResponseStatus(404);
    }

    /**
     * @test
     */
    public function it_should_give_detail_about_particular_namespace() {

        $namespace = factory(App\Project_namespace::class)->create();

        $this->be($namespace->project->owner);

        $request = $this->json('get', "/api/projects/{$namespace->project->id}/namespaces/{$namespace->id}");

        $this->assertResponseStatus(200);

        $request->seeJsonStructure([
            'id',
            'name'
        ]);
    }

    /**
     * @test
     */
    public function it_should_return_404_when_namespace_id_not_found() {

        $namespace = factory(App\Project_namespace::class)->create();

        $this->be($namespace->project->owner);

        $request = $this->json('get', "/api/projects/{$namespace->project->id}/namespaces/dfdsdf");

        $this->assertResponseStatus(404);
    }

    /**
     * @test
     */
    public function it_should_return_404_when_not_authorize_to_the_project() {

        $namespace = factory(App\Project_namespace::class)->create();

        $anotherUser = factory(App\User::class)->create();

        $this->be($anotherUser);

        $request = $this->json('get', "/api/projects/{$namespace->project->id}/namespaces/{$namespace->id}");

        $this->assertResponseStatus(404);
    }

    /**
     * @test
     */
    public function it_should_return_404_when_namespace_not_belong_to_the_project() {

        $namespace = factory(App\Project_namespace::class)->create();

        $anotherProject = factory(App\Project::class)->create();

        $this->be($anotherProject->owner);

        $request = $this->json('get', "/api/projects/{$anotherProject->id}/namespaces/{$namespace->id}");

        $this->assertResponseStatus(404);
    }
}
