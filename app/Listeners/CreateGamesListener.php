<?php

namespace App\Listeners;

use App\Events\ChampionshipCreated;
use App\Services\LeagueService;

class CreateGamesListener
{
    /**
     * Handle the event.
     *
     * @param ChampionshipCreated $event
     * @throws \Throwable
     */
    public function handle(ChampionshipCreated $event): void
    {
        $leagueService = new LeagueService();
        $leagueService->createSchedule($event->championship);
    }
}
