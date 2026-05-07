<div class="p-6 max-w-xl mx-auto bg-white shadow rounded-lg mt-10">
    <h2 class="text-2xl font-bold mb-4 border-b pb-2">Start a New Game</h2>

    @if (session()->has('message'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    <div class="space-y-6">
        <div>
            <label class="block text-sm font-semibold">Matchup / Game Name</label>
            <input type="text" wire:model="gameName" class="w-full border rounded p-2 focus:ring-2 focus:ring-blue-500 @error('gameName') border-red-500 @enderror" placeholder="e.g., Cubs @ Brewers">
            @error('gameName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <hr>

        <div>
            <label class="block text-sm font-semibold">Add Players (In Seating Order)</label>
            <div class="flex gap-2 mt-1">
                <input type="text" wire:model="newPlayerName" wire:keydown.enter="addPlayer" class="flex-1 border rounded p-2 @error('newPlayerName') border-red-500 @enderror" placeholder="Friend's name...">
                <button wire:click="addPlayer" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Add</button>
            </div>
            @error('newPlayerName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="bg-gray-50 rounded-md p-4 min-h-[100px]">
            <h3 class="text-xs uppercase font-bold text-gray-500 mb-2">The Circle</h3>
            <ul class="divide-y">
                @forelse($players as $index => $player)
                    <li class="py-2 flex justify-between items-center group">
                        <span>{{ $index + 1 }}. <strong>{{ $player }}</strong></span>
                        <button wire:click="removePlayer({{ $index }})" class="text-red-400 hover:text-red-600 text-xs opacity-0 group-hover:opacity-100 transition-opacity">Remove</button>
                    </li>
                @empty
                    <li class="text-gray-400 italic">No players added yet. Add people in the order they are sitting!</li>
                @endforelse
            </ul>
        </div>

        @if(count($players) >= 2)
            <div class="pt-4">
                @error('players') <p class="text-red-500 text-center text-sm mb-2">{{ $message }}</p> @enderror
                <button wire:click="createGame" wire:loading.attr="disabled" class="w-full bg-emerald-600 text-white font-bold py-3 rounded shadow hover:bg-emerald-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                    <span wire:loading.remove wire:target="createGame">CREATE GAME</span>
                    <span wire:loading wire:target="createGame">CREATING...</span>
                </button>
            </div>
        @endif
    </div>
</div>
