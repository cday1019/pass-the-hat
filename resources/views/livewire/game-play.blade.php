<div class="p-4 max-w-md mx-auto space-y-6">

    <div wire:poll.3s class="p-4 max-w-md mx-auto space-y-6">

        @if(!$myPlayerId)
            <div class="bg-white p-6 rounded-3xl shadow-xl border-2 border-dashed border-blue-300">
                <h3 class="text-center font-bold text-lg mb-4">Which player are you?</h3>
                <div class="grid grid-cols-2 gap-2">
                    @foreach($game->players as $p)
                        <button wire:click="claimPlayer({{ $p->id }})" class="bg-blue-50 text-blue-700 py-2 rounded-lg font-bold">
                            {{ $p->name }}
                        </button>
                    @endforeach
                </div>
            </div>
        @endif

        @php
            $isCurrentTurn = ($myPlayerId == $game->currentHolder()->id);
        @endphp

        <div class="bg-blue-900 text-white p-8 rounded-3xl text-center shadow-2xl">
            <h2 class="text-xl opacity-75">The Hat Holds</h2>
            <div class="text-6xl font-black mt-2">${{ number_format($game->pot, 2) }}</div>
            <p class="mt-2 text-blue-200">Current Game: {{ $game->name }}</p>
        </div>

        @if($isCurrentTurn)

            <div class="bg-white p-6 rounded-3xl shadow-lg border-4 border-orange-400 text-center">
                <h3 class="text-gray-500 uppercase tracking-widest text-sm font-bold">Currently Holding the Hat</h3>
                <div class="text-4xl font-bold text-gray-800 mt-2">{{ $game->currentHolder()->name }}</div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <button wire:click="handlePlay('out')" class="bg-red-500 text-white p-6 rounded-2xl font-bold text-xl shadow-lg">OUT (-$1)</button>
                <button wire:click="handlePlay('single')" class="bg-green-500 text-white p-6 rounded-2xl font-bold text-xl shadow-lg">SINGLE (+$1)</button>
                <button wire:click="handlePlay('double')" class="bg-green-600 text-white p-6 rounded-2xl font-bold text-xl shadow-lg">DOUBLE (+$2)</button>
                <button wire:click="handlePlay('triple')" class="bg-green-700 text-white p-6 rounded-2xl font-bold text-xl shadow-lg">TRIPLE (+$3)</button>
                <button wire:click="handlePlay('hr')" class="col-span-2 bg-yellow-500 text-black p-6 rounded-2xl font-bold text-2xl shadow-xl">⚾ HOME RUN (TAKE POT)</button>
            </div>
        @else
            <div class="bg-gray-100 p-10 rounded-3xl text-center border-2 border-gray-200">
                <div class="text-4xl mb-2">⏳</div>
                <p class="text-gray-500 font-medium">Wait for your turn...</p>
                <p class="text-xs text-gray-400 mt-2">Currently: {{ $game->currentHolder()->name }} is up</p>
            </div>
        @endif

    </div>


    <div class="mt-8 bg-white rounded-3xl p-6 shadow-lg">
        <h3 class="text-gray-400 uppercase text-xs font-bold mb-4 tracking-widest">Player Standings</h3>
        <div class="space-y-4">
            @foreach($game->players as $p)
                <div class="flex justify-between items-center {{ $game->current_turn_index == $p->sort_order ? 'bg-orange-50 p-2 rounded-lg border border-orange-200' : '' }}">
                    <span class="font-bold">{{ $p->name }}</span>
                    <span class="{{ $p->balance < 0 ? 'text-red-500' : 'text-green-600' }} font-mono font-bold">
                    {{ $p->formatted_balance }}
                </span>
                </div>
            @endforeach
        </div>
    </div>
    <div class="mt-8 bg-gray-900 text-gray-300 rounded-3xl p-6 shadow-xl">
        <h3 class="text-gray-500 uppercase text-xs font-bold mb-4 tracking-widest">Recent Activity</h3>
        <div class="space-y-3">
            @forelse($history as $tx)
                <div class="flex justify-between items-center text-sm border-b border-gray-800 pb-2">
                    <div>
                        <span class="text-blue-400 font-bold">{{ $tx->player->name }}</span>
                        <span class="text-gray-500">hit a</span>
                        <span class="uppercase font-bold text-white">{{ $tx->action }}</span>
                    </div>
                    <div class="font-mono {{ $tx->amount < 0 ? 'text-red-400' : 'text-green-400' }}">
                        {{ $tx->amount > 0 ? '+' : '' }}{{ $tx->amount }}
                    </div>
                </div>
            @empty
                <p class="text-gray-600 italic">Play ball! No history yet.</p>
            @endforelse
        </div>
    </div>
</div>

