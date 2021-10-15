<?php

namespace Tests\Feature;

use Illuminate\Http\Response;
use Tests\TestCase;

class ProductsTest extends TestCase {

    protected $payload = array();

    public function setUp():void
    {
        parent::setUp();
        $this->payload = $this->createNewUser();
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
    public function testOnlyLoggedInUserCanAttachProduct()
    {
        $product = $this->addProducts();
        $this->json('post', 'api/user/products', ['sku' => $product[0]['sku']])
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
        $this->json('post', 'api/auth', $this->payload);
        $this->json('post', 'api/user/products', ['sku' => $product[0]['sku']])
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
    public function testCannotAttachAnInvalidProduct()
    {
        $this->json('post', 'api/auth', $this->payload);
        $this->json('post', 'api/user/products', ['sku' => "random_string"])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /*test*/
    public function testOnlyLoggedInUserCanDetachProduct()
    {
        $product = $this->addProducts();
        $this->json('delete', 'api/user/products/' . $product[0]['sku'])
            ->assertStatus(Response::HTTP_UNAUTHORIZED);
        $this->json('post', 'api/auth', $this->payload);
        $this->json('post', 'api/user/products', ['sku' => $product[0]['sku']]);
        $this->json('delete', 'api/user/products/' . $product[0]['sku'])
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
    public function testCannotDetachAnInvalidProduct()
    {
        $this->json('post', 'api/auth', $this->payload);
        $this->json('delete', 'api/user/products/random_string/')
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}