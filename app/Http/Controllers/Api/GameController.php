<?php

namespace App\Http\Controllers\Api;

use App\Models\Championship;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller as BaseController;
use App\Services\LeagueService;

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
        $games = $this->championship->games()->nextWeek()->get();

        foreach ($games as $game) {
            $results = $this->leagueService->predict($game);
            $game->update($results);
        }

        return response()->json($games);
    }

    public function playAll()
    {
        $games = $this->championship->games()->remainingWeeks()->get();

        foreach ($games as $game) {
            $results = $this->leagueService->predict($game);
            $game->update($results);
        }

        return response()->json($games);
    }
}
