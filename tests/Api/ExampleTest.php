<?php

namespace Tests\Api;

use Tests\Api\TestCase;

class ExampleTest extends TestCase
{
    protected $baseUrl = 'https://reqres.in';
    protected static $prefixApi = 'api';
    /**
     * This method is called before each test.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * This method is called after each test.
     */
    protected function tearDown(): void
    {
    }

    /**
     * @override
     *
     * Add (key, value) to headers before sending a request
     *
     * @return array
     */
    protected function addHeaders()
    {
        return [
            'X-API-TOKEN' => 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx',
            'ACCESS-TOKEN' =>' 123124234345fdgfdgfh456hjkjkjlj'
        ];
    }

    /**
     * @TestCase-1 A basic test example.
     *
     * @Given
     * - Given conditions
     * @When
     * - Actions
     * @Then
     * - Results will be asserted
     */
    public function testBasicTest()
    {
        $response = $this->get('https://jsonplaceholder.typicode.com/posts/1');https://reqres.in/
        $this->assertEquals(1, $response->data->id);
        $this->assertTrue(true);
    }

    /**
     * @TestCase-2 Show user list
     *
     * @Given
     * - At least a user in database
     * @When
     * - Call api for getting user list
     * @Then
     * - See a user list
     */
    public function testShowUserList()
    {
        $response = $this->get(self::url('/users'));
        $this->assertGreaterThan(0, count($response->data->data));
        $this->assertTrue(true);
    }

    /**
     * @TestCase-3 Show user detail
     *
     * @Given
     * - At least a user in database
     * - Given user (user id)
     * @When
     * - Call api for getting user detail
     * @Then
     * - See the detail information of user
     */
    public function testShowUserDetail()
    {
        $userRandom = $this->userRandom();
        $response = $this->get(self::url("/users/{$userRandom->id}"));
        $result = $response->data->data;

        $this->assertTrue($response->success);
        $this->assertEquals($userRandom->id, $result->id);
        $this->assertEquals($userRandom->first_name, $result->first_name);
        $this->assertEquals($userRandom->last_name, $result->last_name);
        $this->assertEquals($userRandom->avatar, $result->avatar);
    }

    /**
     * @TestCase-3 Not find user
     *
     * @Given
     * - At least a user in database
     * - Given user does not exist in database (user id)
     * @When
     * - Call api for getting user detail
     * @Then
     * - No see the given user
     * - Response with Not Found message
     */
    public function testNotFindUser()
    {
        $userIdNoExist = 50;
        $response = $this->get(self::url("/users/{$userIdNoExist}"));

        $this->assertTrue($response->success);
        $this->assertEquals(404, $response->status_code);
        $this->assertEquals('Not Found', $response->status_text);
    }

    /**
     * @TestCase-4 Create new user
     * @Given
     * - At least a user in database
     * - Parameters for creating new user
     * @When
     * - Call api for creating new user
     * @Then
     * - See new user in database
     */
    public function testCreateNewUser()
    {
        //$usersBeforeInsert = $this->users();
        $paramsNewUser = [
            'name' => 'JHP Phich',
            'job' => 'Developer'
        ];
        // insert
        $response = $this->post(self::url('/users'), [
            'json' => $paramsNewUser
        ]);

        //$usersAfterInsert = $this->users();

        $this->assertTrue($response->success);
        $this->assertEquals($paramsNewUser['name'], $response->data->name);
        $this->assertEquals($paramsNewUser['job'], $response->data->job);
        $this->assertGreaterThan(0, $response->data->id);
        // $this->assertEquals($usersBeforeInsert + 1, $usersAfterInsert);
        // $this->assertCount($usersBeforeInsert + 1, $usersAfterInsert);
        // $this->assertContains(['id' => $response->data->data->id], $usersAfterInsert);
        // $this->assertContainsValuePartialOfArray($response, $usersAfterInsert);
    }

    /**
     * Get user list
     *
     * @return array
     */
    private function users()
    {
        $response = $this->get(self::url('/users'));
        return $response->success ? $response->data->data : [];
    }

    /**
     * Get user randomly from given list
     *
     * @return object
     */
    private function userRandom()
    {
        $users = $this->users();
        return $users[array_rand($users)];
    }
}
