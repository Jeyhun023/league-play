<?php
declare(strict_types=1);

namespace App\Http\ViewSet\Front\Home;

use App\Http\Models\Championship;
use App\Http\ViewSet\AbstractViewSet;
use Illuminate\Support\Facades\Session;
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

    protected function getUserSession(): string
    {
        if ($session = Session::has('session')) {
           return $session;
        }

        return Session::put('session', md5(time()));
    }

    protected function getChampionship(): Championship
    {
        return Championship::firstOrCreate([
            'session' => $this->getUserSession()
        ]);
    }
}
