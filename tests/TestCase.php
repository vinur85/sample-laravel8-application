<?php

namespace Tests;

use App\Models\Product;
use Exception;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends BaseTestCase {

    use CreatesApplication, DatabaseMigrations;

    private Generator $faker;

    public function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
        Artisan::call('migrate:refresh');
    }

    public function __get($key) {

        if ($key === 'faker')
            return $this->faker;
        throw new Exception('Unknown Key Requested');
    }

    function createNewUser()
    {
        $payload = [
            'name' => $this->faker->firstName,
            'password' => $this->faker->password,
            'email' => $this->faker->email
        ];
        $this->json('post', 'api/register', $payload)->assertStatus(200);
        return $payload;
    }

    function addProducts()
    {
        $products = array();
        $counter = 1;
        while ($counter <= 5) {
            $products[] = [
                'name' => $this->faker->word,
                'sku' => $this->faker->numberBetween(1, 999)
            ];
            $counter++;
        }
        Product::insert($products);
        return $products;
    }
}