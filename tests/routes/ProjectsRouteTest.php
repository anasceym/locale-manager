<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProjectsRouteTest extends TestCase
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
    public function it_should_display_all_projects() {

        $user = factory(App\User::class)->create();

        $projects = factory(App\Project::class, 5)->create([
            'user_id' => $user->id
        ]);

        $this->be($user);

        $this->visitRoute('projects.index');

        $this->assertResponseOk();
    }
}
