<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $file = Storage::disk('local')->path('products.csv');
        $csvFile = fopen($file, "r");

        $firstLine = true;
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {

            if ($firstLine) {
                $firstLine = false;
                continue;
            }
            if ($data[0] && $data[1]) {
                Product::updateOrCreate([
                    'sku'=> $data[0]
                ],[
                    'sku'=> $data[0],
                    'name' => $data['1'],
                ]);
            }

        }

        fclose($csvFile);
    }
}
