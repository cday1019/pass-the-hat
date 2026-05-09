<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div wire:poll.3s class="grid grid-cols-1 lg:grid-cols-12 gap-4 lg:gap-8">
        {{-- Fixed Header for Mobile --}}
        <div class="lg:hidden bg-slate-900 -mx-4 -mt-8 mb-0 p-4 shadow-xl sticky top-0 z-20">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-blue-400 font-bold uppercase tracking-widest text-[10px]">The Pot</h2>
                    <div class="text-3xl font-black font-display text-white">${{ number_format($game->pot, 2) }}</div>
                </div>
                <div class="text-right">
                    <h2 class="text-slate-500 font-bold uppercase tracking-widest text-[10px]">At Bat</h2>
                    <div class="text-xl font-bold text-white">{{ $game->currentHolder()->name }}</div>
                </div>
            </div>
        </div>

        {{-- Main Game Area (Left/Top) --}}
        <div class="lg:col-span-7 xl:col-span-8 flex flex-col gap-4 lg:gap-8">

            {{-- Player Selection --}}
            @if(!$myPlayerId)
                <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-sm p-8 rounded-3xl shadow-xl border border-blue-100 dark:border-slate-800 animate-in fade-in zoom-in duration-300">
                    <h3 class="text-center font-extrabold text-2xl mb-6 text-slate-800 dark:text-slate-100">Identify Yourself, Slugger!</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                        @foreach($game->players as $p)
                            <button wire:click="claimPlayer({{ $p->id }})"
                                    class="bg-white dark:bg-slate-800 border-2 border-blue-100 dark:border-slate-700 text-blue-600 dark:text-blue-400 hover:bg-blue-600 hover:text-white dark:hover:bg-blue-600 dark:hover:text-white hover:border-blue-600 transition-all py-3 rounded-2xl font-bold shadow-sm hover:shadow-md active:scale-95">
                                {{ $p->name }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            @php
                $isCurrentTurn = ($myPlayerId == $game->currentHolder()->id);
            @endphp

            {{-- The Pot / Scoreboard Card --}}
            <div class="hidden lg:block relative overflow-hidden bg-slate-900 text-white p-8 sm:p-12 rounded-[2.5rem] shadow-2xl group">
                <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-blue-500 rounded-full blur-[80px] opacity-20 group-hover:opacity-40 transition-opacity"></div>
                <div class="relative z-10 flex flex-col items-center text-center">
                    <h2 class="text-blue-400 font-bold uppercase tracking-[0.2em] text-sm mb-4">The Hat Holds</h2>
                    <div class="text-7xl sm:text-8xl font-black font-display tracking-tight drop-shadow-lg animate-pulse">
                        ${{ number_format($game->pot, 2) }}
                    </div>
                    <div class="mt-6 inline-flex items-center px-4 py-2 bg-slate-800/50 rounded-full border border-slate-700 backdrop-blur-md">
                        <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-ping"></span>
                        <span class="text-slate-300 font-medium">Game: {{ $game->name }}</span>
                    </div>
                </div>
            </div>

            {{-- Action Controls --}}
            <div class="flex flex-col gap-4">
                @if($isCurrentTurn)
                    <div class="bg-white dark:bg-slate-900 p-1 rounded-3xl shadow-lg border border-orange-200 dark:border-orange-900/50">
                        <div class="bg-orange-50/50 dark:bg-orange-950/20 rounded-[1.4rem] p-4 sm:p-6 text-center border border-orange-100 dark:border-orange-900/30">
                            <h3 class="text-orange-600 dark:text-orange-400 uppercase tracking-widest text-xs font-black">You're at the plate</h3>
                            <div class="text-4xl font-extrabold text-slate-800 dark:text-slate-100 mt-1">Make Your Play!</div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <button wire:click="handlePlay('out')"
                                wire:confirm="out, you sure about that?"
                                class="flex flex-col items-center justify-center bg-red-500 hover:bg-red-600 text-white p-6 rounded-3xl font-black text-xl shadow-xl transition-all hover:-translate-y-1 active:scale-95">
                            <span class="text-3xl mb-1">❌</span>
                            OUT
                            <span class="text-xs font-medium opacity-80 mt-1">-$1.00</span>
                        </button>
                        <button wire:click="handlePlay('single')"
                                wire:confirm="single, you sure about that?"
                                class="flex flex-col items-center justify-center bg-emerald-500 hover:bg-emerald-600 text-white p-6 rounded-3xl font-black text-xl shadow-xl transition-all hover:-translate-y-1 active:scale-95">
                            <span class="text-3xl mb-1">⚾</span>
                            1B
                            <span class="text-xs font-medium opacity-80 mt-1">+$1.00</span>
                        </button>
                        <button wire:click="handlePlay('double')"
                                wire:confirm="double, you sure about that?"
                                class="flex flex-col items-center justify-center bg-emerald-600 hover:bg-emerald-700 text-white p-6 rounded-3xl font-black text-xl shadow-xl transition-all hover:-translate-y-1 active:scale-95">
                            <span class="text-3xl mb-1">🥈</span>
                            2B
                            <span class="text-xs font-medium opacity-80 mt-1">+$2.00</span>
                        </button>
                        <button wire:click="handlePlay('triple')"
                                wire:confirm="triple, you sure about that?"
                                class="flex flex-col items-center justify-center bg-emerald-700 hover:bg-emerald-800 text-white p-6 rounded-3xl font-black text-xl shadow-xl transition-all hover:-translate-y-1 active:scale-95">
                            <span class="text-3xl mb-1">🥉</span>
                            3B
                            <span class="text-xs font-medium opacity-80 mt-1">+$3.00</span>
                        </button>
                        <button wire:click="handlePlay('hr')"
                                wire:confirm="hr, you sure about that?"
                                class="col-span-2 md:col-span-4 bg-gradient-to-r from-yellow-400 to-orange-500 hover:from-yellow-500 hover:to-orange-600 text-white p-8 rounded-[2rem] font-black text-3xl shadow-2xl transition-all hover:-translate-y-1 active:scale-95 border-b-8 border-orange-700 group">
                            <span class="inline-block group-hover:animate-bounce mr-2">🏆</span>
                            HOME RUN
                            <span class="block text-sm font-bold opacity-90 mt-1 uppercase tracking-widest">Clear the Bases & Take the Pot!</span>
                        </button>
                    </div>
                @else
                    <div class="bg-white/50 dark:bg-slate-900/50 backdrop-blur-sm p-12 rounded-[2.5rem] text-center border-2 border-dashed border-slate-200 dark:border-slate-800">
                        <div class="relative inline-block lg:block">
                            <div class="text-6xl mb-4 animate-bounce hidden lg:block">⏳</div>
                            <div class="absolute -top-1 -right-1 w-4 h-4 bg-orange-500 rounded-full hidden lg:block"></div>
                        </div>
                        <p class="text-slate-400 dark:text-slate-500 font-bold uppercase tracking-widest text-sm">Waiting for Pitch...</p>
                        <p class="text-2xl font-extrabold text-slate-800 dark:text-slate-100 mt-2">
                             <span class="text-blue-600 dark:text-blue-400">{{ $game->currentHolder()->name }}</span> is batting
                        </p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Sidebar (Right/Bottom) --}}
        <div class="lg:col-span-5 xl:col-span-4 flex flex-col gap-4 lg:gap-8">

            {{-- Player Standings --}}
            <div class="bg-white dark:bg-slate-900 rounded-[2rem] p-8 shadow-xl border border-slate-100 dark:border-slate-800">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-slate-800 dark:text-slate-200 uppercase text-sm font-black tracking-widest">Player Standings</h3>
                    <span class="bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 text-[10px] px-2 py-1 rounded-full font-bold">LIVE</span>
                </div>
                <div class="space-y-3">
                    @foreach($game->players as $p)
                        <div class="flex justify-between items-center p-4 rounded-2xl transition-all {{ $game->current_turn_index == $p->sort_order ? 'bg-orange-50 dark:bg-orange-950/30 border-2 border-orange-200 dark:border-orange-800 scale-[1.02] shadow-md' : 'bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800' }}">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center mr-3 text-xs font-bold {{ $game->current_turn_index == $p->sort_order ? 'bg-orange-200 dark:bg-orange-800 text-orange-700 dark:text-orange-200' : 'text-slate-500 dark:text-slate-400' }}">
                                    {{ $loop->iteration }}
                                </div>
                                <span class="font-bold text-slate-700 dark:text-slate-300">{{ $p->name }}</span>
                            </div>
                            <span class="{{ $p->balance < 0 ? 'text-red-500 dark:text-red-400' : 'text-emerald-600 dark:text-emerald-400' }} font-display text-xl">
                                {{ $p->formatted_balance }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Recent Activity --}}
            <div class="bg-slate-900 rounded-[2rem] p-8 shadow-2xl">
                <h3 class="text-slate-500 uppercase text-xs font-black mb-6 tracking-widest flex items-center">
                    <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                    Recent Activity
                </h3>
                <div class="space-y-4 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
                    @forelse($history as $tx)
                        <div class="flex justify-between items-start text-sm bg-slate-800/50 p-4 rounded-xl border border-slate-700/50">
                            <div>
                                <span class="text-blue-400 font-extrabold">{{ $tx->player->name }}</span>
                                <p class="text-slate-400 text-xs mt-1">Hit a <span class="text-white font-bold uppercase tracking-tighter">{{ $tx->action }}</span></p>
                            </div>
                            <div class="font-display text-lg {{ $tx->amount < 0 ? 'text-red-400' : 'text-emerald-400' }}">
                                {{ $tx->amount > 0 ? '+' : '' }}{{ number_format($tx->amount, 2) }}
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <p class="text-slate-600 italic">Play ball! No history yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.05);
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 10px;
    }
</style>

