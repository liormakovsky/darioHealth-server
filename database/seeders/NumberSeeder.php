<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Number;
use Carbon\Carbon;


class NumberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        Number::truncate();
  
        $csvFile = fopen(base_path("database/data/numbers.csv"), "r");
  
        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if (!$firstline) {
                Number::create([
                    "cnt_id" => $data['0'],
                    "num_number" => $data['1'],
                    "num_created" => Carbon::now()
                ]);    
            }
            $firstline = false;
        }
   
        fclose($csvFile);
    }
}
