<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Championship extends Model
{
    use HasFactory;

    protected $fillable = ['session'];

    public function games()
    {
        return $this->hasMany(Game::class);
    }

    public static function getChampionshipProbabilities($standings, $currentWeek)
    {
        $maxWeek = Team::getMaxWeek();
        $totalRemainingPoints = ($maxWeek - $currentWeek) * 3 * count($standings);

        if ($totalRemainingPoints == 0) {
            return null; // Avoid division by zero
        }

        $probabilities = [];
        $totalPoints = 0;
        
        foreach ($standings as $standing) {
            $remainingMatches = ($maxWeek - $currentWeek) * 2; // two matches per week
            $potentialPoints = $standing['PTS'] + $remainingMatches * 3; // assuming a win for all remaining matches
            $totalPoints += $potentialPoints;
        }

        foreach ($standings as $standing) {
            $remainingMatches = ($maxWeek - $currentWeek) * 2; // two matches per week
            $potentialPoints = $standing['PTS'] + $remainingMatches * 3; // assuming a win for all remaining matches
            $probability = $potentialPoints / $totalPoints * 100;
            $probabilities[$standing['team']->name] = $probability;
        }

        return $probabilities;
    }
}
