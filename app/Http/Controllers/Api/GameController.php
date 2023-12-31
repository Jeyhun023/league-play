<?php

namespace App\Http\Controllers\Api;

use App\Models\Game;
use App\Models\Championship;
use Illuminate\Http\Request;
use App\Http\Requests\Api\Game\UpdateRequest;
use App\Http\Controllers\Controller as BaseController;
use App\Services\LeagueService;
use Illuminate\Support\Facades\Cookie;

class GameController extends BaseController
{
    /**
     * @var Championship
     */
    protected $championship;

    /**
     * @var LeagueService
     */
    protected $leagueService;

    public function __construct(Request $request)
    {
        $session = $request->cookie('session');
        $this->championship = Championship::session($session)->firstOrFail();
        $this->leagueService = new LeagueService();
    }

    public function play()
    {
        $currentWeek = $this->championship->games()->where('played', false)->min('week');

        $games = $this->championship->games()->nextWeek($currentWeek)->get();

        foreach ($games as $game) {
            $results = $this->leagueService->predict($game);
            $game->update($results);
        }

        return response()->json([
            'games' => $games,
            'scoreTable' => $this->championship->getScoreTable(),
            'probability' => $this->championship->getChampionshipProbabilities(),
        ]);
    }

    public function playAll()
    {
        $currentWeek = $this->championship->games()->where('played', false)->min('week');

        $games = $this->championship->games()
            ->orderBy('week', 'asc')->remainingWeeks($currentWeek)->get();

        foreach ($games as $game) {
            $results = $this->leagueService->predict($game);
            $game->update($results);
        }

        return response()->json([
            'games' => $games,
            'scoreTable' => $this->championship->getScoreTable(),
            'probability' => $this->championship->getChampionshipProbabilities(),
        ]);
    }

    public function update(UpdateRequest $request, Game $game)
    {
        $game->update([
            'home_team_score' => $request->homeScore,
            'away_team_score' => $request->awayScore
        ]);

        return response()->json([
            'scoreTable' => $this->championship->getScoreTable(),
            'probability' => $this->championship->getChampionshipProbabilities(),
        ]);
    }
}
