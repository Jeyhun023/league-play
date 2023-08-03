<?php
namespace App\Http\ViewSet\Front\Home;

use App\Models\Team;
use App\Models\Game;
use App\Models\Championship;
use App\Http\ViewSet\AbstractViewSet;
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

    public function __construct(IndexRequest $request, Championship $championship)
    {
        $this->request = $request;
        $this->championship = $championship;
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
        return $this->championship->games()
            ->played()->with('homeTeam', 'awayTeam')->orderBy('week')->get();
    }

    public function probabilities(): array
    {
        return $this->championship->getChampionshipProbabilities();
    }

    public function scoreTable(): array
    {
        return $this->championship->getScoreTable();
    }
}
