<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExportApiTest extends TestCase
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
    public function it_should_able_to_export_to_file_for_all_namespaces() {

        $testData = collect([
            'namespace' => 'export_test',
            'lang_codes' => ['en', 'ms', 'kln']
        ]);

        // Initialize all need data

        $project = factory(App\Project::class)->create();

        $namespace = factory(App\Project_namespace::class)->create(['name' => $testData->get('namespace'),'project_id' => $project->id]);

        $langs = collect([]);

        foreach($testData->get('lang_codes') as $code) {

            $langs->push(factory(App\Project_lang::class)->create(['lang_code' => $code, 'project_id' => $project->id]));
        }

        $testTranslationKeys = collect([
            'welcome_text',
            'bye_text',
            'welcome_paragraph'
        ]);

        // Make the request

        $this->json('get', "/api/projects/{$project->id}/export/file");

        // Assert!

        $this->assertResponseStatus(200);
    }
}
