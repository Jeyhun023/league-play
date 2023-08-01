<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'home_team', 
        'away_team', 
        'home_team_score', 
        'away_team_score', 
        'match_date'
    ];
}
