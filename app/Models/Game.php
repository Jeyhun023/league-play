<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Game extends Model
{
    use HasFactory;

    public const WIN_POINT = 3;
    public const DRAFT_POINT = 1;

    public $timestamps = false;

    protected $fillable = [
        'championship_id',
        'home_team', 
        'away_team', 
        'home_team_score', 
        'away_team_score', 
        'week',
        'played'
    ];

    public function homeTeam()
    {
        return $this->belongsTo(Team::class, 'home_team');
    }

    public function awayTeam()
    {
        return $this->belongsTo(Team::class, 'away_team');
    }

    public function scopeNotPlayed(Builder $builder): Builder
    {
        return $builder->where('played', false);
    }

    public function scopePlayed(Builder $builder): Builder
    {
        return $builder->where('played', true);
    }

    public function scopeNextWeek(Builder $builder, $currentWeek): Builder
    {
        return $builder->where('week', $currentWeek ?? 0);
    }

    public function scopeRemainingWeeks(Builder $builder, $currentWeek): Builder
    {
        return $builder->where('week', '>=', $currentWeek ?? 7);
    }
}
