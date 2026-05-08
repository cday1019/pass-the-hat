<div class="p-4 max-w-md mx-auto space-y-6">
    <div class="text-center">
        <h1 class="text-2xl font-black">UMPIRE CONTROL</h1>
        <p class="text-gray-500">Currently Up: <span class="text-orange-600 font-bold">{{ $game->currentHolder()->name }}</span></p>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <button wire:click="handlePlay('out')" class="bg-red-500 text-white p-8 rounded-3xl font-bold text-2xl shadow-xl">OUT</button>
        <button wire:click="handlePlay('single')" class="bg-green-500 text-white p-8 rounded-3xl font-bold text-2xl shadow-xl">1B</button>
        <button wire:click="handlePlay('double')" class="bg-green-600 text-white p-8 rounded-3xl font-bold text-2xl shadow-xl">2B</button>
        <button wire:click="handlePlay('triple')" class="bg-green-700 text-white p-8 rounded-3xl font-bold text-2xl shadow-xl">3B</button>
        <button wire:click="handlePlay('hr')" class="col-span-2 bg-yellow-500 text-black p-8 rounded-3xl font-black text-3xl shadow-xl">HOME RUN</button>
    </div>

    <a href="{{ route('game.play', $game) }}" class="block text-center text-blue-600 font-bold">View Scoreboard</a>
</div>
