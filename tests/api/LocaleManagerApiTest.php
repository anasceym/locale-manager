<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LocaleManagerApiTest extends TestCase
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
    public function it_should_return_all_available_locale() {

        $this->beAuthenticatedUser();

        $response = $this->json('get', '/api/locales');

        $response->seeJson(Config::get('locale'));
    }

    /**
     *
     */
    public function it_should_failed_when_no_auth_token_provided() {

        $this->beAuthenticatedUser();

        $response = $this->json('get', 'api/locales');

        $response->assertResponseStatus(401);
    }

    /**
     * @test
     */
    public function it_should_return_fullname_of_a_locale_code() {

        $this->beAuthenticatedUser();

        $code = 'ms';

        $name = Config::get("locale.{$code}");

        $response = $this->json('get', "/api/locales/{$code}/name");

        $expectedResponse = [
            'code' => $code,
            'name' => $name
        ];

        $response->seeJson($expectedResponse);
        $this->assertEquals($expectedResponse, json_decode($response->response->getContent(), true));
        $this->assertResponseOk();
    }

    /**
     * @test
     */
    public function it_should_return_404_if_no_code_found() {

        $this->beAuthenticatedUser();
        
        $code = 'blablabla';

        $response = $this->json('get', "/api/locales/{$code}/name");

        $this->assertResponseStatus(404);
    }
}
