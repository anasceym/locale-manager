<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Config;

class UtilsTest extends TestCase
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
    public function it_should_return_list_of_locales() {

        $locales = Config::get('locale');

        $this->assertTrue(is_array($locales));
    }

    /**
     * @test
     */
    public function it_should_require_en_ms_fr() {

        $this->assertEquals('Malay', Config::get('locale.ms'));
        $this->assertEquals('English', Config::get('locale.en'));
        $this->assertEquals('French', Config::get('locale.fr'));
    }
}
