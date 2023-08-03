<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Championship extends Model
{
    use HasFactory;

    protected $fillable = ['session'];

    public function games()
    {
        return $this->hasMany(Game::class);
    }

    public function scopeSession(Builder $builder, $session): Builder
    {
        return $builder->where('session', $session);
    }

    public function getChampionshipProbabilities()
    {
        $standings = $this->getScoreTable();
        $maxWeek = Team::getMaxWeek();
        $totalRemainingPoints = ($maxWeek - 1) * 3 * count($this->getScoreTable($standings));

        if ($totalRemainingPoints == 0) {
            return null; // Avoid division by zero
        }

        $probabilities = [];
        $totalPoints = 0;
        
        foreach ($standings as $standing) {
            $remainingMatches = ($maxWeek - 1) * 2; // two matches per week
            $potentialPoints = $standing['PTS'] + $remainingMatches * 3; // assuming a win for all remaining matches
            $totalPoints += $potentialPoints;
        }

        foreach ($standings as $standing) {
            $remainingMatches = ($maxWeek - 1) * 2; // two matches per week
            $potentialPoints = $standing['PTS'] + $remainingMatches * 3; // assuming a win for all remaining matches
            $probability = $potentialPoints / $totalPoints * 100;
            $probabilities[$standing['team']->name] = round($probability);
        }

        return $probabilities;
    }

    public function getScoreTable()
    {
        // Retrieve games for the current championship
        $games = $this->games()->played()->get();

        // Initialize standings
        $standings = [];

        foreach (Team::all() as $team) {
            $standings[$team->id] = [
                'team' => $team,
                'PTS' => 0,
                'P' => 0,
                'W' => 0,
                'D' => 0,
                'L' => 0,
                'GD' => 0,
            ];
        }

        // Calculate standings
        foreach ($games as $match) {
            $home = &$standings[$match->home_team];
            $away = &$standings[$match->away_team];

            $home['P']++;
            $away['P']++;

            $home['GD'] += ($match->home_team_score - $match->away_team_score);
            $away['GD'] += ($match->away_team_score - $match->home_team_score);

            if ($match->home_team_score > $match->away_team_score) {
                $home['W']++;
                $away['L']++;
                $home['PTS'] += 3;
            } elseif ($match->home_team_score < $match->away_team_score) {
                $home['L']++;
                $away['W']++;
                $away['PTS'] += 3;
            } else {
                $home['D']++;
                $away['D']++;
                $home['PTS'] += 1;
                $away['PTS'] += 1;
            }
        }

        // Sort by points
        usort($standings, function ($a, $b) {
            return $b['PTS'] - $a['PTS'];
        });

        return $standings;
    }
}
