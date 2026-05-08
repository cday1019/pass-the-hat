<?php

namespace App\Livewire;

use App\Models\Game;
use Livewire\Component;

class GameControl extends Component
{
    public Game $game;

    public function mount(Game $game) {
        $this->game = $game;
    }

    public function handlePlay($action) {
        $this->game->recordPlay($action);
        $this->game->refresh();
    }

    public function render() {
        return view('livewire.game-control');
    }
}
