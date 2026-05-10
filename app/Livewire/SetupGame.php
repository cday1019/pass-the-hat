<?php

namespace App\Livewire;

use App\Models\Game;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class SetupGame extends Component
{
    public $gameName = '';
    public $newPlayerName = '';
    public $players = []; // Temporary storage for the names before we save to DB
    public $anteAmount = 1;
    public $availableGames = [];
    public $selectedGamePk = '';

    public function mount()
    {
        try {
            $response = Http::get('https://statsapi.mlb.com/api/v1/schedule?sportId=1&date=2026-05-10');
            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['dates'][0]['games'])) {
                    foreach ($data['dates'][0]['games'] as $game) {
                        $this->availableGames[] = [
                            'gamePk' => $game['gamePk'],
                            'description' => $game['teams']['away']['team']['name'] . ' @ ' . $game['teams']['home']['team']['name'],
                        ];
                    }
                }
            }
        } catch (\Exception $e) {
            // Handle error silently or log it
        }
    }

    public function updatedSelectedGamePk($value)
    {
        if ($value) {
            foreach ($this->availableGames as $game) {
                if ($game['gamePk'] == $value) {
                    $this->gameName = $game['description'];
                    break;
                }
            }
        }
    }
    public function addPlayer()
    {
        $this->validate([
            'newPlayerName' => 'required|min:2|max:50',
        ], [
            'newPlayerName.required' => 'Enter a name.',
            'newPlayerName.min' => 'Name too short.',
        ]);

        $this->players[] = $this->newPlayerName;
        $this->reset('newPlayerName');
    }

    public function removePlayer($index)
    {
        unset($this->players[$index]);
        $this->players = array_values($this->players);
    }

    public function createGame()
    {
        $this->validate([
            'gameName' => 'required|min:3',
            'players' => 'array|min:2', // Ensure at least 2 players
            'anteAmount' => 'required|numeric|min:0.01',
        ]);

        return DB::transaction(function () {
            // 1. Create the Game record
            $game = Game::create([
                'name' => $this->gameName ?: 'New Baseball Game',
                'mlb_game_pk' => $this->selectedGamePk,
                'pot' => 0,
                'ante_amount' => $this->anteAmount,
                'current_turn_index' => 0
            ]);

            // 2. Loop through our temporary names and create Player records
            foreach ($this->players as $index => $name) {
                $game->players()->create([
                    'name' => $name,
                    'sort_order' => $index,
                    'balance' => 0
                ]);
            }

            // ... after $game and players are created
            session()->put("game_{$game->id}_is_host", true);
            session()->save();

            $game->collectAntes($this->anteAmount);

            // 3.  success
            return redirect()->route('game.play', $game->id);
            });
    }

    public function render()
    {
        return view('livewire.setup-game');
    }
}
