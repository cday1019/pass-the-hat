<?php

namespace App\Livewire;

use App\Models\Game;
use Livewire\Component;

class GamePlay extends Component
{
    public Game $game; // Laravel will automatically find the game by its ID
    public $myPlayerId;

    public function mount(Game $game)
    {
        $this->game = $game;
        $this->myPlayerId = session("game_{$game->id}_player_id");
    }

    public function handlePlay($action, $amount)
    {
        // Just call the model!
        $this->game->recordPlay($action, $amount);
        $this->game->refresh();
    }

    public function claimPlayer($id)
    {
        // Save the choice to the session and update the component
        session(["game_{$this->game->id}_player_id" => $id]);
        $this->myPlayerId = $id;
    }

    public function render()
    {
        $isHost = session("game_{$this->game->id}_is_host", false);

        return view('livewire.game-play', [
            'isHost' => $isHost, // Pas
            'history' => $this->game->transactions()
                ->with('player') // This is "Eager Loading"
                ->latest()
                ->take(5)
                ->get()
        ]);
    }
}
