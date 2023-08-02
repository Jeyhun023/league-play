<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'strength'];

    public static function getMaxWeek($matchesPerWeek = 2) {
        $totalTeams = self::all()->count();
        $totalMatches = $totalTeams * ($totalTeams - 1);

        return $totalMatches / $matchesPerWeek;
    }
}
