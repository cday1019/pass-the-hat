<?php

namespace App\Livewire;

use App\Models\Game;
use App\Services\MlbFeedService;
use Livewire\Component;

class LivePlayLog extends Component
{
    public Game $game;

    public function render(MlbFeedService $mlbFeedService)
    {
        $plays = [];
        if ($this->game->mlb_game_pk) {
            $plays = $mlbFeedService->getLatestPlays($this->game->mlb_game_pk);
        }

        return view('livewire.live-play-log', [
            'plays' => $plays,
        ]);
    }
}
