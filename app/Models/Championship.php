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
        $WIN_POINTS = Game::WIN_POINT;

        $currentWeek = Game::where('championship_id', $this->id)->where('played', true)->max('week');
        $totalWeeks = Team::getMaxWeek();

        $scoreTable = $this->getScoreTable(); 

        $teams = [];
        foreach ($scoreTable as $row) {
            $teams[] = [
                'name' => $row['team']->name,
                'points' => $row['PTS']
            ];
        }

        // Sort by current points
        usort($teams, function ($a, $b) {
            return $b['points'] - $a['points'];
        });

        // Calculate remaining matches
        $remainingMatches = $totalWeeks - $currentWeek;

        // Check if the top team is unreachable by others
        $maxPointsForSecondTeam = $teams[1]['points'] + ($WIN_POINTS * $remainingMatches);
        if ($teams[0]['points'] > $maxPointsForSecondTeam) {
            $probabilities[$teams[0]['name']] = 100;
            for ($i = 1; $i < count($teams); $i++) {
                $probabilities[$teams[$i]['name']] = 0;
            }
            return $probabilities;
        }

        // Calculate the probability based on max possible points
        $totalMaxPoints = 0;
        foreach ($teams as $team) {
            $totalMaxPoints += $team['points'] + ($WIN_POINTS * $remainingMatches);
        }
        foreach ($teams as $team) {
            $probabilities[$team['name']] = round((($team['points'] + ($WIN_POINTS * $remainingMatches)) / $totalMaxPoints) * 100);
        }

        return $probabilities;
    }

    public function getScoreTable()
    {
        $games = $this->games()->played()->get();

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
