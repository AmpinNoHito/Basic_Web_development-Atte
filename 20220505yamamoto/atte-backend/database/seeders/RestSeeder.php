<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rest;

class RestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i < 400; $i++) {
            Rest::create([
                'work_id' => $i,
                'start' => '12:00:00',
                'end' => '13:00:00',
            ]);
        }
    }
}
