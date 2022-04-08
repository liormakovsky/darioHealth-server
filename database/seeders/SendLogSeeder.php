<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SendLog;
use Carbon\Carbon;


class SendLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        SendLog::truncate();
  
        $csvFile = fopen(base_path("database/data/log_message.csv"), "r");
  
        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if (!$firstline) {
                SendLog::create([
                    "usr_id" => $data['0'],
                    "num_id" => $data['1'],
                    "log_messsage" => $data['2'],
                    "log_success" => $data['3'],
                    "log_created" => Carbon::parse($data['4']),
                ]);    
            }
            $firstline = false;
        }
   
        fclose($csvFile);
    }
}
