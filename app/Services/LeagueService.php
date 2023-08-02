<?php

namespace App\Services;

use App\Models\Game;
use App\Models\Team;
use App\Models\Championship;

class LeagueService
{
    public function createSchedule(Championship $championship): void
    {
        $teams = Team::pluck('id')->toArray();

        //Randomize teams for each meetup
        shuffle($teams);

        for ($round = 1; $round <= Team::getMaxWeek(); $round += 2) {
            for ($i = 0; $i < count($teams) / 2; $i++) {
                $team1 = $teams[$i];
                $team2 = $teams[count($teams) - 1 - $i];
                // Skipping matches with null (bye)
                if ($team1 && $team2) {
                    $schedule[] = [
                        'championship_id' => $championship->id,
                        'home_team' => $team1,
                        'away_team' => $team2,
                        'week' => $round
                    ];
                    $schedule[] = [
                        'championship_id' => $championship->id,
                        'home_team' => $team2,
                        'away_team' => $team1,
                        'week' => $round + 1
                    ];
                }
            }
            // Rotating the teams
            array_splice($teams, 1, 0, array_splice($teams, -1));
        }
    
        Game::insert($schedule);
    }
}
