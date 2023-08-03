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

    public function predict(Game $game): array
    {
        $homeTeam = $game->homeTeam;
        $awayTeam = $game->awayTeam;

        // Team Power Indicator
        $homeTeamPower = $homeTeam->power;
        $awayTeamPower = $awayTeam->power;

        // Home Advantage
        $homeTeamPower += 10;

        // Weather Conditions
        $weather = $this->simulateWeather();
        $homeTeamPower += $this->weatherEffect($weather, $homeTeam);
        $awayTeamPower += $this->weatherEffect($weather, $awayTeam);

        // Form 
        $homeTeamPower += $this->calculateForm($homeTeam, $game);
        $awayTeamPower += $this->calculateForm($awayTeam, $game);

        // Tactics and Strategy 
        $homeTeamPower += $this->applyTactics($homeTeam, $awayTeam);
        $awayTeamPower += $this->applyTactics($awayTeam, $homeTeam);

        // Calculate final score, adding some randomness
        $homeGoals = max(0, round($homeTeamPower / 10) + random_int(-2, 2));
        $awayGoals = max(0, round($awayTeamPower / 10) + random_int(-2, 2));

        // Create match result
        $matchResult = [
            'home_team_score' => $homeGoals,
            'away_team_score' => $awayGoals,
            'played' => true
        ];

        return $matchResult;
    }

    private function simulateWeather()
    {
        $weatherConditions = ["sunny", "rainy", "windy"];
        return $weatherConditions[array_rand($weatherConditions)];
    }

    private function weatherEffect($weather, $team)
    {
        $effect = 0;

        switch ($weather) {
            case "sunny":
                // No effect
                break;
            case "rainy":
                // If the team relies on fast, technical play, rain might be a disadvantage
                if ($team->play_style == 'technical') {
                    $effect = -10;
                }
                // Conversely, a physical, defensively strong team might benefit
                else if ($team->play_style == 'physical') {
                    $effect = 5;
                }
                break;
            case "windy":
                // Wind might affect teams that rely on aerial play
                if ($team->playStyle == 'aerial') {
                    $effect = -15;
                }
                break;
        }

        return $effect;
    }

    private function calculateForm($team, $game)
    {
        $recentMatches = Game::where('home_team', $team->id)
                            ->orWhere('away_team', $team->id)
                            ->where('week', '<', $game->week)
                            ->orderBy('week', 'desc')
                            ->limit(3)
                            ->get();

        $form = 0;

        foreach ($recentMatches as $match) {
            // Determine if the team was playing at home or away
            $isHomeTeam = $match->home_team == $team->id;

            // Determine the goals for and against this team in the match
            $goalsFor = $isHomeTeam ? $match->home_goals : $match->away_goals;
            $goalsAgainst = $isHomeTeam ? $match->away_goals : $match->home_goals;

            // Apply form modifiers based on the result
            if ($goalsFor > $goalsAgainst) {
                $form += 10; // Win
            } elseif ($goalsFor == $goalsAgainst) {
                $form += 5; // Draw
            } else {
                $form -= 10; // Loss
            }
        }

        return $form;
    }

    private function applyTactics($team, $opponent)
    {
        $modifier = 0;

        // Example: If the team's chosen tactic is to press high and the opponent struggles against pressing
        if ($team->tactic == 'high_press' && $opponent->weakness == 'press_resistance') {
            $modifier += 5;
        }

        // Example: If the team chooses to defend deeply and the opponent struggles to break down deep defenses
        if ($team->tactic == 'deep_defense' && $opponent->weakness == 'breaking_defense') {
            $modifier += 10;
        }

        // Example: If the team chooses an attacking formation but the opponent is strong on counter-attacks
        if ($team->tactic == 'attacking' && $opponent->strength == 'counter_attack') {
            $modifier -= 10;
        }

        return $modifier;
    }
}
