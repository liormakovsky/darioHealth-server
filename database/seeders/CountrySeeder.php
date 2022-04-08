<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Country;
use Carbon\Carbon;


class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        Country::truncate();
  
        $csvFile = fopen(base_path("database/data/countries.csv"), "r");
  
        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if (!$firstline) {
                Country::create([
                    "cnt_code" => $data['0'],
                    "cnt_title" => $data['1'],
                    "cnt_created" => Carbon::now()
                ]);    
            }
            $firstline = false;
        }
   
        fclose($csvFile);
    }
}
