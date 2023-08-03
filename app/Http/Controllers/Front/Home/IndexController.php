<?php

namespace App\Http\Controllers\Front\Home;

use App\Models\Championship;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;
use App\Http\Requests\Front\Home\IndexRequest;
use App\Http\Controllers\Controller as BaseController;

class IndexController extends BaseController
{
    public function index(IndexRequest $request)
    {
        $data = $request->getViewSet($this->getChampionship());

        return view('front.index', ['data' => $data]);
    }

    protected function getUserSession(): string
    {
        $token = Cookie::get('session') ?? Str::random(60);
        Cookie::queue(Cookie::make('session', $token, 60));

        return $token;
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

    public function reset()
    {
        $token = Str::random(60);
        Cookie::queue(Cookie::make('session', $token, 60));

        return redirect()->route('front::home.index');
    }
}
