<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Carbon\Carbon;
use Hash;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        User::truncate();
  
        $csvFile = fopen(base_path("database/data/users.csv"), "r");
  
        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if (!$firstline) {
                User::create([
                    "usr_name" => $data['0'],
                    "email" => $data['1'],
                    "password" => Hash::make($data['2']),
                    "usr_active" => $data['3'],
                    "usr_created" => Carbon::now()
                ]);    
            }
            $firstline = false;
        }
   
        fclose($csvFile);
    }
}
