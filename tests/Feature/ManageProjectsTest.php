<?php

namespace Tests\Feature;

use App\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Facades\Tests\Setup\ProjectFactory;
use Tests\TestCase;

class ManageProjectsTest extends TestCase
{
    use WithFaker, RefreshDatabase;


    /**
     * @test
     */
    public function guest_cannot_manage_project()
    {
        $project = factory('App\Project')->create();

        $this->get('/projects')->assertRedirect('login');
        $this->get('/projects/create')->assertRedirect('login');
        $this->get($project->path() . '/edit')->assertRedirect('login');
        $this->get($project->path())->assertRedirect('login');
        $this->post('/projects', $project->toArray())->assertRedirect('login');

    }

    /**
     * @test
     */
    public function a_user_can_create_a_project()
    {

        $this->signIn();

        $this->get('/projects/create')->assertStatus(200);

        $this->followingRedirects()
            ->post('/projects', $attributes = factory(Project::class)->raw())
            ->assertSee($attributes['title'])
            ->assertSee($attributes['description'])
            ->assertSee($attributes['notes']);
    }

    /**
     * @test
     */
    public function a_user_can_update_a_project()
    {
        $project = ProjectFactory::create();
        $this->actingAs($project->owner)
            ->patch($project->path(), $attribute = ['title' => 'changed', 'description' => 'changed', 'notes' => 'changed'])
            ->assertRedirect($project->path());

        $this->get($project->path() . '/edit')->assertOk();

        $this->assertDatabaseHas('projects', $attribute);
    }

    /**
     * @test
     */
    function a_user_can_see_all_projects_they_have_been_invited_to_on_their_dashboard()
    {

        $project = tap(ProjectFactory::create())->invite($this->signIn());

        $this->get('/projects')->assertSee($project->title);

    }



    /** @test */
    function unauthorized_users_cannot_delete_projects()
    {

        $project = ProjectFactory::create();

        $this->delete($project->path())
            ->assertRedirect('/login');

        $user = $this->signIn();

        $this->delete($project->path())->assertStatus(403);
        $project->invite($user);
        $this->actingAs($user)->delete($project->path())->assertStatus(403);
    }

    /** @test */
    function a_user_can_delete_a_project()
    {

        $project = ProjectFactory::create();

        $this->actingAs($project->owner)
            ->delete($project->path())
            ->assertRedirect('/projects');

        $this->assertDatabaseMissing('projects', $project->only('id'));

    }

    /**
     * @test
     */
    public function a_user_can_update_a_project__general_notes()
    {
        $project = ProjectFactory::create();
        $this->actingAs($project->owner)
            ->patch($project->path(), $attribute = ['notes' => 'changed']);

        $this->assertDatabaseHas('projects', $attribute);
    }

    /**
     * @test
     */
    public function a_user_can_view_their_project()
    {
        $project = ProjectFactory::create();


        $this->actingAs($project->owner)
            ->get($project->path())
            ->assertSee($project->title)
            ->assertSee($project->description);

        $project = factory('App\Project')->create();

        $this->patch($project->path())->assertStatus(403);

    }

    /**
     * @test
     */
    public function a_authenticated_user_cannot_view_the_projects_of_others()
    {
        $this->signIn();

        $project = factory('App\Project')->create();

        $this->get($project->path())->assertStatus(403);
    }

    /**
     * @test
     */
    public function a_authenticated_user_update_view_the_projects_of_others()
    {
        $this->signIn();

        $project = factory('App\Project')->create();

        $this->patch($project->path(), [])->assertStatus(403);
    }

    /**
     * @test
     */
    public function a_project_requires_a_title()
    {
        $this->signIn();
        $attribute = factory('App\Project')->raw(['title' => '']);

        $this->post('/projects', $attribute)->assertSessionHasErrors('title');
    }

    /**
     * @test
     */
    public function a_project_requires_a_description()
    {
        $this->signIn();

        $attribute = factory('App\Project')->raw(['description' => '']);

        $this->post('/projects', $attribute)->assertSessionHasErrors('description');
    }

}
