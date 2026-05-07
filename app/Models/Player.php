<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Player extends Model
{
    protected $guarded = [];

    protected function formattedBalance(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->balance < 0
                ? '-$' . number_format(abs($this->balance), 2)
                : '$' . number_format($this->balance, 2)
        );
    }

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
