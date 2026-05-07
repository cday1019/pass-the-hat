<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $guarded = [];

    public function players()
    {
        return $this->hasMany(Player::class)->orderBy('sort_order');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class)->latest();
    }

    // Helper to get the person currently holding the hat
    public function currentHolder(): ?Player
    {
        // Checks if the players relation is already loaded to save a query
        return $this->players->firstWhere('sort_order', $this->current_turn_index);
    }

    public function passTheHat(): void
    {
        $totalPlayers = $this->players()->count();
        $nextIndex = $this->current_turn_index + 1;

        // If we reached the end of the circle, go back to the first person (0)
        if ($nextIndex >= $totalPlayers) {
            $nextIndex = 0;
        }

        $this->update(['current_turn_index' => $nextIndex]);
    }

    public function recordPlay(string $action, float $amount): void
    {
        $player = $this->currentHolder();

        if (!$player) {
            return;
        }

        if ($action === 'out') {
            $player->decrement('balance', 1);
            $this->increment('pot', 1);
        } elseif ($action === 'hr') {
            $player->increment('balance', $this->pot);
            $this->update(['pot' => 0]);
        } else {
            $payout = min(abs($amount), $this->pot);
            $player->increment('balance', $payout);
            $this->decrement('pot', $payout);
        }

        $this->transactions()->create([
            'player_id' => $player->id,
            'action' => $action,
            'amount' => $amount,
        ]);

        $this->passTheHat();
    }
}
