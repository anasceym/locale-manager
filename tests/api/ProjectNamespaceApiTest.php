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
}
