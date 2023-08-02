<?php

namespace App\Events;

use App\Models\Championship;
use Illuminate\Queue\SerializesModels;

class ChampionshipCreated
{
    use SerializesModels;

    /**
     * @var Championship
     */
    public $championship;

    /**
     * Create a new event instance.
     *
     * @param Championship $championship
     */
    public function __construct(Championship $championship)
    {
        $this->championship = $championship;
    }
}
