<?php

namespace Tests\Feature;

use Illuminate\Http\Response;
use Tests\TestCase;

class UserTest extends TestCase {

    protected $payload = array();

    public function setUp():void
    {
        parent::setUp();
        $this->payload = $this->createNewUser();
    }

    /*test*/
    public function testUserCanRegister()
    {
        $payload = [
            'name' => $this->faker->firstName,
            'password' => $this->faker->password,
            'email' => $this->faker->email
        ];
        $this->json('post', 'api/register')->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $apiResponse = $this->json('post', 'api/register', $payload);
        $this->assertEquals('{"message":"Registered successfully!!!","data":[]}', $apiResponse->getContent());
    }

    /*test*/
    public function testUserLogin()
    {
        $this->json('post', 'api/auth')->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $apiResponse = $this->json('post', 'api/auth', $this->payload);
        $this->assertEquals('{"message":"Logged in successfully!!!","data":[]}', $apiResponse->getContent());
    }

    /*test*/
    public function testUserCanViewDetailsOnlyAfterLogin() {

        $this->json('get', 'api/user')
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJsonStructure([
                'message'
            ]);

        $this->json('post', 'api/auth')->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $this->json('post', 'api/auth', $this->payload);
        $user = $this->call('get', 'api/user')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'name'
                ]
            ]);
        $strResponse = '{"message":"Success","data":{"name":"' . $this->payload['name'] . '"}}';
        $this->assertEquals($strResponse, $user->getContent());
    }

    /*test*/
    public function testUserCanViewHisProductsOnlyAfterLogin() {

        $this->json('get', 'api/user/products')
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJsonStructure([
                'message'
            ]);

        $this->json('post', 'api/auth', $this->payload);
        $this->call('get', 'api/user/products')
            ->assertStatus(Response::HTTP_OK);
    }

    /*test*/
    public function testAnyoneCanViewListOfProducts()
    {
        $this->addProducts();
        $this->json('get', 'api/products')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data'=>[
                    '*'=>[
                        'sku',
                        'name'
                    ]
                ], 'meta' => [
                    'total'
                ]
            ]);
    }

    /*test*/
    public function testOnlyAuthenticatedUserCanLogout()
    {
        $this->json('post', 'api/logout')
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJsonStructure([
                'message'
            ]);

        $this->json('post', 'api/auth', $this->payload);
        $this->call('post', 'api/logout', array(), [])->assertOk();
    }
}