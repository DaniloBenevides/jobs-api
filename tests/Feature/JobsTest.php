<?php

namespace Tests\Feature;

use App\Models\Job;
use App\Models\User;
use App\Models\Role;
use App\Notifications\JobCreated;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class JobsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @return void
     */
    public function it_should_not_create_jobs_as_a_guest()
    {
        $job = Job::factory()->make();

        $response = $this->postJson(route("jobs.store"), $job->toArray());

        $response->assertStatus(401);

        $this->assertDatabaseMissing('jobs', $job->toArray());
    }

    /**
     * @test
     * @return void
     */
    public function it_should_not_create_jobs_with_empty_description()
    {
        $user = User::factory()->create();

        $job = Job::factory()->make([
            "description" => ""
        ]);

        $response = $this->actingAs($user)
            ->postJson(route("jobs.store"), $job->toArray());

        $response->assertStatus(422);

        $this->assertDatabaseMissing('jobs', $job->toArray());
    }

    /**
     * @test
     * @return void
     */
    public function it_should_not_create_jobs_with_empty_title()
    {
        $user = User::factory()->create();

        $job = Job::factory()->make([
            "title" => ""
        ]);

        $response = $this->actingAs($user)
            ->postJson(route("jobs.store"), $job->toArray());

        $response->assertStatus(422);
        $this->assertDatabaseMissing('jobs', $job->toArray());
    }

    /**
     * @test
     * @return void
     */
    public function it_should_create_job_with_valid_data()
    {
        $user = User::factory()->create();
        $role = Role::factory()->manager()->create();

        $user->roles()->attach($role->id);

        $job = Job::factory()->make();

        $response = $this->actingAs($user)
            ->postJson(route("jobs.store"), $job->toArray());

        $response->assertStatus(201);
        $this->assertDatabaseHas('jobs', $job->toArray());
    }

     /**
     * @test
     * @return void
     */
    public function it_should_notify_managers_when_a_regular_user_creates_a_job()
    {
        Notification::fake();

        $regular_role = Role::factory()->regular()->create();
        $manager_role = Role::factory()->manager()->create();

        $regular_user = User::factory()->create();
        $regular_user->roles()->attach($regular_role->id);

        $manager_user_1 = User::factory()->create();
        $manager_user_1->roles()->attach($manager_role->id);

        $manager_user_2 = User::factory()->create();
        $manager_user_2->roles()->attach($manager_role->id);

        Notification::assertNothingSent();
        $job = Job::factory()->make();

        $response = $this->actingAs($regular_user)
            ->postJson(route("jobs.store"), $job->toArray());

        // Assert a notification was sent to the given users...
        Notification::assertSentTo(
            [$manager_user_1, $manager_user_2], JobCreated::class
        );

        $response->assertStatus(201);
        $this->assertDatabaseHas('jobs', $job->toArray());
    }

    /**
     * @test
     * @return void
     */
    public function it_should_not_notify_regular_users_when_a_job_is_created()
    {
        Notification::fake();

        $regular_role = Role::factory()->regular()->create();

        $regular_user_1 = User::factory()->create();
        $regular_user_1->roles()->attach($regular_role->id);

        $regular_user_2 = User::factory()->create();
        $regular_user_2->roles()->attach($regular_role->id);

        $regular_user_3= User::factory()->create();
        $regular_user_3->roles()->attach($regular_role->id);


        Notification::assertNothingSent();
        $job = Job::factory()->make();

        $response = $this->actingAs($regular_user_1)
            ->postJson(route("jobs.store"), $job->toArray());

        // Assert a notification was not sent to the given users...
        Notification::assertNotSentTo(
            [$regular_user_2, $regular_user_3], JobCreated::class
        );

        $response->assertStatus(201);
        $this->assertDatabaseHas("jobs", $job->toArray());
    }
}
