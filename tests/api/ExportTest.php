<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Storage;

class ExportTest extends TestCase
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
    public function it_should_export_to_local_storage_with_php_format() {

        $availableLang = [
            'en',
            'fr',
            'ms'
        ];

        // Read from DB
        $data = collect([
            'test' => [
                'en' => 'test',
                'fr' => 'testo',
                'ms' => 'try'
            ]
        ]);

        foreach ($availableLang as $lang) {

            $filename = "{$lang}_test.php";

            // Delete first if the file exist
            if (Storage::disk('local')->has($filename)) {

                Storage::disk('local')->delete($filename);

                $this->assertFalse(Storage::disk('local')->has($filename));
            }

            // Export now
            Storage::disk('local')->put($filename, '<?php');

            foreach ($data as $key => $langArray) {

            }

        }

        // Assert
        $this->assertTrue(Storage::disk('local')->has($filename));
    }
}
