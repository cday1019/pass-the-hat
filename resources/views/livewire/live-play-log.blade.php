<div wire:poll.30s class="bg-slate-900 rounded-[2rem] p-8 shadow-2xl">
    <h3 class="text-slate-500 uppercase text-xs font-black mb-6 tracking-widest flex items-center">
        <span class="w-2 h-2 bg-red-500 rounded-full mr-2 animate-pulse"></span>
        Live MLB Feed
    </h3>
    <div class="space-y-4 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
        @forelse($plays as $play)
            <div wire:key="play-{{ $play['about']['atBatIndex'] ?? $loop->index }}" class="flex flex-col text-sm bg-slate-800/50 p-4 rounded-xl border border-slate-700/50">
                <div class="flex justify-between mb-1">
                    <span class="text-blue-400 font-extrabold uppercase text-[10px] tracking-widest">
                        Inning {{ $play['about']['inning'] }} - {{ $play['about']['halfInning'] === 'top' ? 'Top' : 'Bottom' }}
                    </span>
                    @if(isset($play['about']['atBatIndex']))
                        <span class="text-slate-600 text-[10px]">#{{ $play['about']['atBatIndex'] }}</span>
                    @endif
                </div>
                <p class="text-slate-300 leading-relaxed italic">
                    {{ $play['result']['description'] ?? 'Play in progress...' }}
                </p>
            </div>
        @empty
            <div class="text-center py-8">
                <p class="text-slate-600 italic">
                    @if($game->mlb_game_pk)
                        Connecting to MLB feed...
                    @else
                        No MLB Game ID linked.
                    @endif
                </p>
            </div>
        @endforelse
    </div>
</div>
