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
        return $this->hasMany(Transaction::class)->latest('id');
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

    public function recordPlay(string $action): void
    {
        $player = $this->currentHolder();

        if (!$player) {
            return;
        }

        $amount = match ($action) {
            'out' => -1.0,
            'single' => 1.0,
            'double' => 2.0,
            'triple' => 3.0,
            'hr' => (float) $this->pot,
            default => 0.0,
        };

        if($amount > $this->pot){
            $amount = $this->pot;
        }

        $this->transactions()->create([
            'player_id' => $player->id,
            'action' => $action,
            'amount' => $amount,
        ]);

        $player->increment('balance', $amount);
        $this->decrement('pot', $amount);

        if($this->pot == 0){
            $this->collectAntes();
        }

        $this->passTheHat();
    }

    public function collectAntes(?float $amount = null): void
    {
        $amount = $amount ?? (float) $this->ante_amount;

        \DB::transaction(function () use ($amount) {
            $players = $this->players;
            $count = $players->count();

            // 1. Bulk update player balances
            $this->players()->update([
                'balance' => \DB::raw("balance - $amount")
            ]);

            // 2. Bulk update game pot
            $this->increment('pot', $amount * $count);

            // 3. Batch insert transactions
            $now = now();
            $transactions = $players->map(function ($player) use ($amount, $now) {
                return [
                    'game_id' => $this->id,
                    'player_id' => $player->id,
                    'action' => 'ante',
                    'amount' => -$amount,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            })->toArray();

            Transaction::insert($transactions);
        });
    }
}
