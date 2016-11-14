<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TranslationKeyApiTest extends TestCase
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
     * Route : api.projects.namespaces.translation_keys
     *
     * @test
     */
    public function it_should_return_all_translation_keys_for_specific_project_namespace() {

        $namespace = factory(App\Project_namespace::class)->create();

        $project = $namespace->project;

        $project_lang = factory(App\Project_lang::class)->create([
            'project_id' => $project->id
        ]);

        $owner = $project->owner;

        $translation_keys = factory(App\Translation_key::class, 2)->create([
            'project_id' => $project->id,
            'project_namespace_id' => $namespace->id
        ]);

        $this->be($owner, 'api');

        $response = $this->json('GET', "/api/projects/{$project->id}/namespaces/{$namespace->id}/translation_keys");

        $expectedJsonStructure = [
            '*' => [
                'id',
                'translation_key'
            ]
        ];

        $this->assertResponseStatus(200);

        $response->seeJsonStructure($expectedJsonStructure);
    }

    /**
     * Route : api.projects.namespaces.translation_keys
     *
     * @test
     */
    public function it_should_return_404_if_project_not_belong_to_the_user() {

        $namespace = factory(App\Project_namespace::class)->create();

        $project = $namespace->project;

        $anotherUser = factory(App\User::class)->create();

        $this->be($anotherUser, 'api');

        $response = $this->json('GET', "/api/projects/{$project->id}/namespaces/{$namespace->id}/translation_keys");

        $this->assertResponseStatus(404);
    }

    /**
     * Route : api.projects.namespaces.translation_keys
     *
     * @test
     */
    public function it_should_return_404_if_namespace_not_belong_to_the_project() {

        $namespace = factory(App\Project_namespace::class)->create();

        $project = factory(App\Project::class)->create();

        $owner = $project->owner;

        $this->be($owner, 'api');

        $response = $this->json('GET', "/api/projects/{$project->id}/namespaces/{$namespace->id}/translation_keys");

        $this->assertResponseStatus(404);
    }

    /**
     * Route : api.projects.namespaces.translation_keys.create
     *
     * @test
     */
    public function it_should_be_able_to_create_new_translation_key() {

        $namespace = factory(App\Project_namespace::class)->create();

        $project = $namespace->project;

        $owner = $project->owner;

        $this->be($owner, 'api');

        $postData = [
            'translation_key' => 'testo'
        ];

        $response = $this->json('POST', "/api/projects/{$project->id}/namespaces/{$namespace->id}/translation_keys", $postData);

        $this->assertResponseStatus(201);
        $this->seeInDatabase('translation_keys', [ 'translation_key' => $postData['translation_key']]);
    }

    /**
     * Route : api.projects.namespaces.translation_keys.create
     *
     * @test
     */
    public function it_should_return_422_when_no_translation_key_sent() {

        $namespace = factory(App\Project_namespace::class)->create();

        $project = factory(App\Project::class)->create();

        $owner = $project->owner;

        $this->be($owner, 'api');

        $postData = [
            'translation_key2' => 'testo'
        ];

        $response = $this->json('POST', "/api/projects/{$project->id}/namespaces/{$namespace->id}/translation_keys", $postData);

        $this->assertResponseStatus(422);
    }

    /**
     * Route : api.projects.namespaces.translation_keys.create
     *
     * @test
     */
    public function it_should_return_404_when_unauthorized_project_not_belong_to_the_user() {

        $namespace = factory(App\Project_namespace::class)->create();

        $project = factory(App\Project::class)->create();

        $anotherUser = factory(App\User::class)->create();

        $this->be($anotherUser, 'api');

        $postData = [
            'translation_key' => 'testo'
        ];

        $response = $this->json('POST', "/api/projects/{$project->id}/namespaces/{$namespace->id}/translation_keys", $postData);

        $this->assertResponseStatus(404);
    }

    /**
     * Route : api.projects.namespaces.translation_keys.create
     *
     * @test
     */
    public function it_should_return_404_when_namespace_not_belongs_to_the_project() {

        $namespace = factory(App\Project_namespace::class)->create();

        $project = factory(App\Project::class)->create();

        $owner = $project->owner;

        $this->be($owner, 'api');

        $postData = [
            'translation_key' => 'testo'
        ];


        $response = $this->json('POST', "/api/projects/{$project->id}/namespaces/{$namespace->id}/translation_keys", $postData);

        $this->assertResponseStatus(404);
    }
}
