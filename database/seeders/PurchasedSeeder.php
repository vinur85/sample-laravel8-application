<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Purchased;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class PurchasedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Purchased::first()) {
            return;
        }
        $file = Storage::disk('local')->path('purchased.csv');
        $csvFile = fopen($file, "r");

        $firstLine = true;
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {

            if ($firstLine) {
                $firstLine = false;
                continue;
            }

            if ($data[0] && $data[1]) {
                $user = User::find($data[0]);
                $product = Product::where('sku', $data[1])->first();
                $user->products()->attach($product['id']);
            }
        }

        fclose($csvFile);
    }
}
