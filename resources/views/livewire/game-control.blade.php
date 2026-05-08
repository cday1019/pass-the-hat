<div class="max-w-md mx-auto py-12 px-4 sm:px-6">
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-2xl border border-slate-100 dark:border-slate-800 overflow-hidden">
        <div class="bg-slate-900 dark:bg-slate-950 p-8 text-center">
            <h1 class="text-white font-display text-4xl tracking-tight mb-2 uppercase">UMPIRE CONTROL</h1>
            <div class="inline-flex items-center px-3 py-1 bg-orange-500/20 rounded-full border border-orange-500/30">
                <span class="text-orange-400 text-xs font-black uppercase tracking-widest mr-2">AT BAT:</span>
                <span class="text-white font-bold">{{ $game->currentHolder()->name }}</span>
            </div>
        </div>

        <div class="p-8 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <button wire:click="handlePlay('out')"
                        class="flex flex-col items-center justify-center bg-red-500 hover:bg-red-600 text-white p-8 rounded-3xl font-black text-2xl shadow-lg transition-all active:scale-95">
                    <span class="text-3xl mb-1">❌</span>
                    OUT
                </button>
                <button wire:click="handlePlay('single')"
                        class="flex flex-col items-center justify-center bg-emerald-500 hover:bg-emerald-600 text-white p-8 rounded-3xl font-black text-2xl shadow-lg transition-all active:scale-95">
                    <span class="text-3xl mb-1">⚾</span>
                    1B
                </button>
                <button wire:click="handlePlay('double')"
                        class="flex flex-col items-center justify-center bg-emerald-600 hover:bg-emerald-700 text-white p-8 rounded-3xl font-black text-2xl shadow-lg transition-all active:scale-95">
                    <span class="text-3xl mb-1">🥈</span>
                    2B
                </button>
                <button wire:click="handlePlay('triple')"
                        class="flex flex-col items-center justify-center bg-emerald-700 hover:bg-emerald-800 text-white p-8 rounded-3xl font-black text-2xl shadow-lg transition-all active:scale-95">
                    <span class="text-3xl mb-1">🥉</span>
                    3B
                </button>
                <button wire:click="handlePlay('hr')"
                        class="col-span-2 bg-gradient-to-r from-yellow-400 to-orange-500 text-white p-10 rounded-3xl font-display text-4xl shadow-xl transition-all active:scale-95 border-b-8 border-orange-700 uppercase">
                    🏆 HOME RUN
                </button>
            </div>

            <div class="pt-6 border-t border-slate-100 dark:border-slate-800 flex justify-center">
                <a href="{{ route('game.play', $game) }}"
                   class="inline-flex items-center text-slate-400 dark:text-slate-500 hover:text-blue-600 dark:hover:text-blue-400 font-bold transition-colors group text-sm">
                    <svg class="w-4 h-4 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to Scoreboard
                </a>
            </div>
        </div>
    </div>
</div>
