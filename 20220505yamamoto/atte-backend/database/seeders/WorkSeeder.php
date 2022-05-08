<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Work;
use Carbon\Carbon;

class WorkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 80; $i++) {
            for ($j = 5; $j >= 1; $j--) { 
                Work::create([
                    'user_id' => $i,
                    'date' => Carbon::now()->modify('-'.$j.'day')->format('Y-n-j'),
                    'start' => '10:00:00',
                    'end' => '18:00:00',
                ]);
            }
        }
    }
}
