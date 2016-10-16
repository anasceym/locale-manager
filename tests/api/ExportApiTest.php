<?php

use App\Translation;
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
            'namespaces' => ['auth', 'messages'],
            'lang_codes' => ['en_US', 'it_CH', 'ms']
        ]);

        // Initialize all need data

        $project = factory(App\Project::class)->create();

        $testTranslationKeys = [];

        foreach($testData['namespaces'] as $namespaceValue) {

            $namespace = factory(App\Project_namespace::class)->create(['name' => $namespaceValue,'project_id' => $project->id]);

            if ($namespaceValue == 'auth') {

                $testTranslationKeys[$namespace->id] = [
                    'auth_text',
                    'logout_text',
                    'remember_me_text'
                ];
            }

            if ($namespaceValue == 'messages') {

                $testTranslationKeys[$namespace->id] = [
                    'success_message',
                    'error_text',
                    'warning_text',
                    'info_text',
                ];
            }
        }

        $langs = collect([]);

        foreach($testData->get('lang_codes') as $code) {

            $projectLang = factory(App\Project_lang::class)->create(['lang_code' => $code, 'project_id' => $project->id]);

            $langs->push($projectLang);

            $faker = Faker\Factory::create($code);

            foreach($testTranslationKeys as $namespace => $texts) {

                foreach($texts as $textKey) {

                    Translation::createFromKeyValue($textKey, $faker->realText(50,2), $project->id, $projectLang->id, $namespace);
                }
            }
        }

        // Make the request

        $request = $this->json('get', "/api/projects/{$project->id}/export/file");

        dd($request->response->getContent());

        // Assert!

        $this->assertResponseStatus(200);
    }
}
