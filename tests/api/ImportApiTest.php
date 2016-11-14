<?php

use App\Translation;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;

class ImportApiTest extends TestCase
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
    public function it_should_return_404_if_import_method_not_allowed() {

        $project = factory(App\Project::class)->create();

        $this->be($project->owner, 'api');

        $postData = [];

        $request = $this->json('post', "/api/projects/{$project->id}/import/zzzzz", $postData);

        $this->assertResponseStatus(404);
    }

    /**
     * @test
     */
    public function it_should_return_422_if_validation_failed_to_import() {

        $project = factory(App\Project::class)->create();

        $this->be($project->owner, 'api');

        $postData = [];

        $request = $this->json('post', "/api/projects/{$project->id}/import/file", $postData);

        $this->assertResponseStatus(422);
    }

    /**
     * @test
     */
    public function it_should_return_404_when_unauthorized_to_the_project() {

        $project = factory(App\Project::class)->create();

        $anotherUser = factory(App\User::class)->create();

        $this->be($anotherUser, 'api');

        $request = $this->json('post', "/api/projects/{$project->id}/import/file");

        $this->assertResponseStatus(404);
    }

    /**
     * @test
     */
    public function it_should_return_422_when_failed_to_parse_php_array() {

        $testingData = collect([
            'namespace' => 'fail_parse',
            'lang_code' => 'en'
        ]);

        $project = factory(App\Project::class)->create();

        $this->be($project->owner, 'api');;

        $namespace = factory(App\Project_namespace::class)->create(['name' => $testingData->get('namespace'), 'project_id' => $project->id]);

        $lang = factory(App\Project_lang::class)->create(['lang_code' => $testingData->get('lang_code'), 'project_id' => $project->id]);

        $path = $this->getTestResourceFolder();

        $fileName = "{$lang->lang_code}_{$namespace->name_key}.php";

        $uploadedFile = new UploadedFile($path . $fileName, $fileName, null, null, null, true);

        $postData = [
            'file' => $uploadedFile,
            'project_lang_id' => $lang->id,
            'project_namespace_id' => $namespace->id
        ];

        $request = $this->json('post', "/api/projects/{$project->id}/import/file", $postData);

        $this->assertResponseStatus(422);
    }

    /**
     * @test
     */
    public function it_should_able_to_import_file_from_upload() {

        $testingData = collect([
            'namespace' => 'test',
            'lang_code' => 'en'
        ]);

        $project = factory(App\Project::class)->create();

        $this->be($project->owner, 'api');;

        $namespace = factory(App\Project_namespace::class)->create(['name' => $testingData->get('namespace'), 'project_id' => $project->id]);

        $lang = factory(App\Project_lang::class)->create(['lang_code' => $testingData->get('lang_code'), 'project_id' => $project->id]);

        $path = $this->getTestResourceFolder();

        $fileName = "{$lang->lang_code}_{$namespace->name_key}.php";

        $uploadedFile = new UploadedFile($path . $fileName, $fileName, null, null, null, true);

        $testFileContent = include($path.$fileName);

        $postData = [
            'file' => $uploadedFile,
            'project_lang_id' => $lang->id,
            'project_namespace_id' => $namespace->id
        ];

        $request = $this->json('post', "/api/projects/{$project->id}/import/file", $postData);

        $this->assertResponseStatus(200);

        if (is_array($testFileContent)) {

            foreach($testFileContent as $key => $value) {
                $this->seeInDatabase('translations', [
                    'project_namespace_id' => $namespace->id,
                    'project_lang_id' => $lang->id,
                    'text_key' => $key,
                    'text_value' => $value
                ]);
            }
        }
    }

    /**
     * @test
     */
    public function it_should_ignore_if_certain_key_already_exists() {

        $testingData = collect([
            'namespace' => 'test',
            'lang_code' => 'en'
        ]);

        $project = factory(App\Project::class)->create();

        $this->be($project->owner, 'api');;

        $namespace = factory(App\Project_namespace::class)->create(['name' => $testingData->get('namespace'), 'project_id' => $project->id]);

        $lang = factory(App\Project_lang::class)->create(['lang_code' => $testingData->get('lang_code'), 'project_id' => $project->id]);

        $path = $this->getTestResourceFolder();

        $fileName = "{$lang->lang_code}_{$namespace->name_key}.php";

        $uploadedFile = new UploadedFile($path . $fileName, $fileName, null, null, null, true);

        $testFileContent = include($path.$fileName);

        $postData = [
            'file' => $uploadedFile,
            'project_lang_id' => $lang->id,
            'project_namespace_id' => $namespace->id
        ];

        // Before request, make sure the translations already in DB
        if (is_array($testFileContent)) {

            foreach($testFileContent as $key => $value) {

                $preparedCreateData = [
                    'project_lang_id' => $lang->id,
                    'project_namespace_id' => $namespace->id,
                    'project_id' => $project->id,
                    'text_key' => $key,
                    'text_value' => $value
                ];

                $translation = Translation::create($preparedCreateData);
            }
        }

        $request = $this->json('post', "/api/projects/{$project->id}/import/file", $postData);

        $this->assertResponseStatus(200);

        if (is_array($testFileContent)) {

            foreach($testFileContent as $key => $value) {

                $count = Translation::where('project_lang_id', $lang->id)
                    ->where('project_namespace_id', $namespace->id)
                    ->where('project_id', $project->id)
                    ->where('text_key', $key)
                    ->where('text_value', $value)->count();

                $this->assertEquals(1, $count);
            }
        }
    }

    /**
     * @return string
     */
    private function getTestResourceFolder()
    {
        return getcwd() . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR;
    }
}
