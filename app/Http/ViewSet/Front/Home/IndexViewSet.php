<?php
namespace App\Http\ViewSet\Front\Home;

use App\Models\Team;
use App\Models\Game;
use App\Models\Championship;
use App\Http\ViewSet\AbstractViewSet;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Requests\Front\Home\IndexRequest;

class IndexViewSet extends AbstractViewSet
{
    /**
     * @var IndexRequest
     */
    private $request;

    /**
     * @var Championship
     */
    protected $championship;

    public function __construct(IndexRequest $request)
    {
        $this->request = $request;
        $this->championship = $this->getChampionship();
    }

    public function request(): IndexRequest
    {
        return $this->request;
    }

    public function championship(): Championship
    {
        return $this->championship;
    }

    public function games(): Collection
    {
        return $this->championship->games()->get()->groupBy('week');
    }

    public function probabilities(): array
    {
        return Championship::getChampionshipProbabilities($this->scoreTable(), 5);
    }

    public function scoreTable(): array
    {
        // Retrieve games for the current championship
        $games = Game::where('championship_id', $this->championship->id)->get();

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

    protected function getUserSession(): string
    {
        if (!Session::has('session')) {
            Session::put('session', Str::random(60));
        }

        return $this->request->session()->get('session');
    }

    protected function getChampionship(): Championship
    {
        $championship = Championship::firstOrCreate([
            'session' => $this->getUserSession()
        ]);

        if ($championship->wasRecentlyCreated) {
            event(new \App\Events\ChampionshipCreated($championship));
        }

        return $championship;
    }
}
