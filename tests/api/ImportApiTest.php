<?php

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
    public function it_should_able_to_import_file_from_upload() {

        $project = factory(App\Project::class)->create();

        $path = getcwd().DIRECTORY_SEPARATOR.'tests'.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR;

        $langCode = 'en';

        $namespace = 'test';

        $fileName = "{$langCode}_{$namespace}.php";

        $uploadedFile = new UploadedFile($path . $fileName, $fileName);

        $postData = [
            'file' => $uploadedFile,
            'lang_code' => $langCode,
            'namespace' => $namespace
        ];

        $this->json('post', "/api/projects/{$project->id}/import/file", $postData);

        $this->assertResponseStatus(200);
    }
}
