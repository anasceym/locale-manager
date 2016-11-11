<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProjectLangApiTest extends TestCase
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
    public function it_should_set_project_lang() {

        $project = factory(App\Project::class)->create();

        $this->be($project->owner, 'api');

        $langKeys = collect(collect(Config::get('locale'))->keys());

        $langKey = $langKeys->random();

        $postData = [
            'lang_code' => $langKey
        ];

        $expectedResponseJsonStructure = [
            'id',
            'lang_code',
        ];

        $request = $this->json('post', "/api/projects/{$project->id}/lang", $postData);

        $this->assertResponseStatus(201);
        $request->seeJsonStructure($expectedResponseJsonStructure);
    }

    /**
     * @test
     */
    public function it_should_return_422_when_validation_fail_for_adding_project_lang() {

        $project = factory(App\Project::class)->create();

        $this->be($project->owner, 'api');

        $request = $this->json('post', "/api/projects/{$project->id}/lang");

        $this->assertResponseStatus(422);

        $request->seeJsonStructure($this->getErrorExpectedStructure());
    }

    /**
     * @test
     */
    public function it_should_return_409_when_adding_project_lang_with_existed_code() {

        $langKeys = collect(collect(Config::get('locale'))->keys());

        $langKey = $langKeys->random();

        $sameCode = $langKey;

        $existingProjectLang = factory(App\Project_lang::class)->create(['lang_code' => $sameCode]);

        $this->be($existingProjectLang->project->owner, 'api');

        $postData = [
            'lang_code' => $sameCode
        ];

        $expectedResponseJsonStructure = [
            'id',
            'lang_code',
        ];

        $request = $this->json('post', "/api/projects/{$existingProjectLang->project->id}/lang", $postData);

        $this->assertResponseStatus(409);
        $request->seeJsonStructure($expectedResponseJsonStructure);
    }

    /**
     * @test
     */
    public function it_should_return_404_if_unauthorize_to_add_project_lang() {

        $project = factory(App\Project::class)->create();

        $otherUser = factory(App\User::class)->create();

        $this->be($otherUser, 'api');

        $langKeys = collect(collect(Config::get('locale'))->keys());

        $langKey = $langKeys->random();

        $postData = [
            'lang_code' => $langKey
        ];

        $request = $this->json('post', "/api/projects/{$project->id}/lang", $postData);

        $this->assertResponseStatus(404);
    }

    /**
     * @test
     */
    public function it_should_return_422_when_lang_code_not_found() {

        $project = factory(App\Project::class)->create();

        $this->be($project->owner, 'api');

        $postData = [
            'lang_code' => 'asdfhgkdlsafqoe'
        ];

        $request = $this->json('post', "/api/projects/{$project->id}/lang", $postData);

        $this->assertResponseStatus(422);

        $request->seeJsonStructure($this->getErrorExpectedStructure());
    }

    /**
     * @test
     */
    public function it_should_delete_a_project_lang() {

        $project_lang = factory(App\Project_lang::class)->create();

        $this->be($project_lang->project->owner, 'api');

        $request = $this->json('delete', "/api/projects/{$project_lang->project->id}/lang/{$project_lang->id}");

        $this->assertResponseStatus(204);

        $this->dontSeeInDatabase('project_langs', ['id' => $project_lang->id, 'deleted_at' => null]);
    }

    /**
     * @test
     */
    public function it_should_return_404_if_the_lang_not_belong_to_the_project() {

        $project_lang = factory(App\Project_lang::class)->create();

        $another_project_lang = factory(App\Project_lang::class)->create();

        $this->be($project_lang->project->owner, 'api');

        $request = $this->json('delete', "/api/projects/{$project_lang->project->id}/lang/{$another_project_lang->id}");

        $this->assertResponseStatus(404);
    }

    /**
     * @test
     */
    public function it_should_return_404_if_unauthorize_to_the_project() {

        $project_lang = factory(App\Project_lang::class)->create();

        $anotherUser = factory(App\User::class)->create();

        $this->be($anotherUser, 'api');

        $request = $this->json('delete', "/api/projects/{$project_lang->project->id}/lang/{$project_lang->id}");

        $this->assertResponseStatus(404);
    }


    /**
     * @test
     */
    public function it_should_accept_lang_code_for_the_route_parameter() {

        $project_lang = factory(App\Project_lang::class)->create();

        $this->be($project_lang->project->owner, 'api');

        $request = $this->json('delete', "/api/projects/{$project_lang->project->id}/lang/{$project_lang->lang_code}");

        $this->assertResponseStatus(204);

        $this->dontSeeInDatabase('project_langs', ['id' => $project_lang->id, 'deleted_at' => null]);
    }
}
