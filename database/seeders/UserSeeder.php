<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $file = Storage::disk('local')->path('users.csv');
        $csvFile = fopen($file, "r");

        $firstLine = true;
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {

            if ($firstLine) {
                $firstLine = false;
                continue;
            }
            if ($data[1] && $data[2] && $data[3]) {
                User::updateOrCreate([
                    'email'=> $data[2]
                ],[
                    "id" => $data['0'] ?? 0,
                    "name" => $data['1'],
                    "email" => $data['2'],
                    "password" => Hash::make($data['3']),
                ]);
            }

        }

        fclose($csvFile);
    }
}
