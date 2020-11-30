<?php

namespace Tests\Api;

use Tests\Api\TestCase;

class ExampleTest extends TestCase
{
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
     * addHeaders
     *
     * @return void
     */
    protected function addHeaders()
    {
        return [
            'X-API-TOKEN' => 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx',
            'ACCESS-TOKEN' =>' 123124234345fdgfdgfh456hjkjkjlj'
        ];
    }

    /**
     * @TestCase-1.1 A basic test example.
     *
     * @Given
     * dfgdfgdfgdfgdfg
     * @When
     *
     * @Then
     *
     * @return void
     */
    public function testBasicTest()
    {
        $response = $this->get('https://jsonplaceholder.typicode.com/posts/1');
        $this->assertEquals(1, $response->data->id);
        $this->assertTrue(true);
    }
}
