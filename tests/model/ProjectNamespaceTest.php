<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProjectNamespaceTest extends TestCase
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
    public function it_should_parse_name_to_namekey() {

        $namespace = factory(App\Project_namespace::class)->make(['name' => 'Kiddos Test']);

        $this->assertEquals($namespace->name_key , 'kiddos_test');
    }
}
