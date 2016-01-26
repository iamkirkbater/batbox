<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Kirkbater\Testing\SoftDeletes;
use Batbox\Models\User;
use Teapot\HttpResponse\Status\StatusCode as HTTP;

class UserControllerTest extends TestCase
{
    use WithoutMiddleware;
    use DatabaseTransactions;
    use SoftDeletes;

    public function setUp()
    {
        parent::setUp();
        $this->prepare();
    }

    public function test_users_page_exists()
    {
        $users = User::all();
        $this->visit("/users")
            ->see('users');
    }

    public function test_single_user_exists()
    {
        $user = $this->generateTestUser();
        $user->save();
        $this->seeInDatabase("users", $this->getTestUserData());
        $id = $user->id;
        unset($user);

        $user = User::find($id);
        $this->visit("/users/".$id)
            ->seeJsonContains(["first" => $user->first])
            ->seeJsonContains(["last" => $user->last])
            ->seeJsonContains(["username" => $user->username])
            ->seeJsonContains(["id" => $user->id]);
    }

    public function test_user_not_found()
    {
        $response = $this->call('get', '/users/-1');
        $this->assertEquals(HTTP::NO_CONTENT, $response->status());
    }

    public function test_add_new_user_successfully()
    {
        $user = $this->getTestUserData();

        $response = $this->call('post', '/users', $user);
        $this->assertEquals(HTTP::CREATED, $response->status());
        $this->seeJsonContains($user);
    }

    public function test_fail_on_adding_new_user()
    {
        $user = [];

        $response = $this->call('post', '/users');
        $this->assertEquals(HTTP::BAD_REQUEST, $response->status());

        $response = $this->call('post', '/users', $user);
        $this->assertEquals(HTTP::BAD_REQUEST, $response->status());
        $this->seeError();
        $this->see("First Name");
        $this->see("Last Name");
        $this->see("Username");

        $user["first"] = "Test";
        $response = $this->call('post', '/users', $user);
        $this->assertEquals(HTTP::BAD_REQUEST, $response->status());
        $this->seeError();
        $this->see("Last Name");
        $this->see("Username");

        $user = [];
        $user["last"] = "Last";
        $response = $this->call('post', '/users', $user);
        $this->assertEquals(HTTP::BAD_REQUEST, $response->status());
        $this->seeError();
        $this->see("First Name");
        $this->see("Username");

        $user = [];
        $user["username"] = "txltwc";
        $response = $this->call('post', '/users', $user);
        $this->assertEquals(HTTP::BAD_REQUEST, $response->status());
        $this->seeError();
        $this->see("First Name");
        $this->see("Last Name");
    }

    public function test_patch_an_existing_user()
    {
        // Create a test user so we can change it, then verify that it's there.
        $testUser = $this->generateTestUser();
        $testUser->save();
        $this->seeInDatabase('users', $this->getTestUserData());

        $user = [
            "first" => "Updated",
        ];
        $response = $this->call('patch', '/users/'.$testUser->id, $user);
        $this->assertEquals(HTTP::OK, $response->status());
        $this->seeJsonContains(["first" => "Updated"]);

        $testUser->save();

        $user = [
            "last" => "Updated",
        ];
        $response = $this->call('patch', '/users/' . $testUser->id, $user);
        $this->assertEquals(HTTP::OK, $response->status());
        $this->seeJsonContains(["last" => "Updated"]);

        $testUser->save();

        $user = [
            "username" => "updted",
        ];
        $response = $this->call('patch', '/users/' . $testUser->id, $user);
        $this->assertEquals(HTTP::OK, $response->status());
        $this->seeJsonContains(["username" => "updted"]);
    }

    public function test_fail_patch_users()
    {
        $response = $this->call('patch', '/users/-1', []);
        $this->assertEquals(HTTP::NOT_MODIFIED, $response->status());

        $testUser = $this->generateTestUser();
        $testUser->save();
        $this->seeInDatabase('users', $this->getTestUserData());

        $response = $this->call('patch', '/users/'.$testUser->id, []);
        $this->assertEquals(HTTP::NOT_MODIFIED, $response->status());
    }

    public function test_delete_a_user_successfully()
    {
        $user = $this->generateTestUser();
        $user->save();
        $this->seeInDatabase("users", $this->getTestUserData());

        $response = $this->call('delete', '/users/'.$user->id, []);
        $this->assertEquals(HTTP::OK, $response->status());
        $this->seeInDatabase("users", $this->getTestUserData());
        $this->seeIsSoftDeletedInDatabase("users", $this->getTestUserData());
    }

    public function test_fail_to_delete_a_user()
    {
        $response = $this->call('delete', '/users/-1', []);
        $this->assertEquals(HTTP::NOT_MODIFIED, $response->status());
    }

    private function setupDB()
    {
        \Artisan::call('migrate');
        \Artisan::call('db:seed');
    }

    private function prepare()
    {
        $this->setupDB();
        \Mail::pretend(true);
    }

    /**
     * @return User
     */
    private function generateTestUser()
    {
        $testUser = new User();
        $testUser->fill($this->getTestUserData());
        return $testUser;
    }

    private function getTestUserData()
    {
        return [
            "first" => "Test",
            "last" => "Name",
            "username" => "txltwc"
        ];
    }
}