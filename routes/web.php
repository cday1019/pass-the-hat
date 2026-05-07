<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\SetupGame;
use App\Livewire\GamePlay;
use App\Livewire\GameControl;

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/', SetupGame::class);
Route::get('/game/{game}', GamePlay::class)->name('game.play');
Route::get('/game/{game}/control', GameControl::class)->name('game.control');
