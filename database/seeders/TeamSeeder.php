<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('teams')->insert([
            [
                'name' => 'Manchester City', 
                'play_style' => 'aerial',
                'tactic' => 'high_press',
                'strength' => 'counter_attack',
                'weakness' => 'breaking_defense',
                'power' => 20
            ],
            [
                'name' => 'Liverpool', 
                'play_style' => 'physical',
                'tactic' => 'deep_defense',
                'strength' => 'full_defense',
                'weakness' => 'press_resistance',
                'power' => 15
            ],
            [
                'name' => 'Chelsea', 
                'play_style' => 'technical',
                'tactic' => 'attacking',
                'strength' => 'counter_attack',
                'weakness' => 'breaking_defense',
                'power' => 10
            ],
            [
                'name' => 'Arsenal', 
                'play_style' => 'physical',
                'tactic' => 'attacking',
                'strength' => 'counter_attack',
                'weakness' => 'press_resistance',
                'power' => 5
            ],
        ]);
    }
}
