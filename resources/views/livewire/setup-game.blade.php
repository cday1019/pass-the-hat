<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white/70 backdrop-blur-md p-10 rounded-[2.5rem] shadow-2xl border border-white/20">
        <div class="text-center">
            <h2 class="mt-2 text-4xl font-black font-display text-slate-900 tracking-tight">START THE GAME</h2>
            <p class="mt-2 text-sm text-slate-500 font-medium uppercase tracking-widest">Setup your baseball "Hat" session</p>
        </div>

        @if (session()->has('message'))
            <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 px-4 py-3 rounded-2xl text-sm font-bold flex items-center animate-in fade-in slide-in-from-top-2">
                <span class="mr-2">✅</span> {{ session('message') }}
            </div>
        @endif

        <div class="mt-8 space-y-6">
            <div class="space-y-4">
                <div class="group">
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-1 ml-1 group-focus-within:text-blue-500 transition-colors">Matchup / Game Name</label>
                    <input type="text" wire:model="gameName"
                           class="block w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl text-slate-900 font-bold placeholder-slate-300 focus:outline-none focus:border-blue-500 focus:bg-white transition-all sm:text-sm"
                           placeholder="e.g., Cubs @ Brewers">
                    @error('gameName') <span class="text-red-500 text-xs font-bold mt-1 ml-1">{{ $message }}</span> @enderror
                </div>

                <div class="group">
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-1 ml-1 group-focus-within:text-blue-500 transition-colors">Ante Amount ($)</label>
                    <div class="relative">
                        <span class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 font-bold">$</span>
                        <input type="number" wire:model="anteAmount" step="0.01"
                               class="block w-full pl-9 pr-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl text-slate-900 font-bold placeholder-slate-300 focus:outline-none focus:border-blue-500 focus:bg-white transition-all sm:text-sm"
                               placeholder="1.00">
                    </div>
                    @error('anteAmount') <span class="text-red-500 text-xs font-bold mt-1 ml-1">{{ $message }}</span> @enderror
                </div>

                <div class="pt-4">
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3 ml-1">Add Players (Seating Order)</label>
                    <div class="flex gap-2 p-1 bg-slate-100 rounded-2xl">
                        <input type="text" wire:model="newPlayerName" wire:keydown.enter="addPlayer"
                               class="flex-1 px-4 py-3 bg-transparent border-none focus:ring-0 text-slate-900 font-bold placeholder-slate-400 text-sm"
                               placeholder="Friend's name...">
                        <button wire:click="addPlayer"
                                class="bg-white text-slate-900 px-6 py-3 rounded-xl font-black text-xs uppercase shadow-sm hover:shadow-md active:scale-95 transition-all">
                            Add
                        </button>
                    </div>
                    @error('newPlayerName') <span class="text-red-500 text-xs font-bold mt-1 ml-1">{{ $message }}</span> @enderror
                </div>

                <div class="bg-slate-50/50 rounded-2xl p-4 border-2 border-dashed border-slate-100">
                    <h3 class="text-[10px] uppercase font-black text-slate-400 mb-3 tracking-[0.2em] flex items-center">
                        <span class="w-1.5 h-1.5 bg-blue-500 rounded-full mr-2"></span>
                        The Lineup
                    </h3>
                    <ul class="space-y-2">
                        @forelse($players as $index => $player)
                            <li class="py-2 px-3 bg-white rounded-xl shadow-sm flex justify-between items-center group animate-in fade-in slide-in-from-left-2">
                                <span class="text-sm font-bold text-slate-700">
                                    <span class="text-slate-300 mr-2">{{ $index + 1 }}</span>
                                    {{ $player }}
                                </span>
                                <button wire:click="removePlayer({{ $index }})"
                                        class="text-red-300 hover:text-red-500 p-1 rounded-lg hover:bg-red-50 transition-all opacity-0 group-hover:opacity-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </li>
                        @empty
                            <li class="text-slate-400 italic text-xs py-4 text-center">No players yet. Add your crew!</li>
                        @endforelse
                    </ul>
                </div>

                @if(count($players) >= 2)
                    <div class="pt-6">
                        @error('players') <p class="text-red-500 text-center text-xs font-bold mb-3">{{ $message }}</p> @enderror
                        <button wire:click="createGame" wire:loading.attr="disabled"
                                class="w-full bg-slate-900 text-white font-black py-5 rounded-2xl shadow-xl hover:bg-slate-800 hover:-translate-y-1 active:scale-95 transition-all disabled:opacity-50 tracking-[0.2em] text-sm">
                            <span wire:loading.remove wire:target="createGame">PLAY BALL!</span>
                            <span wire:loading wire:target="createGame" class="flex items-center justify-center">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                PREPARING...
                            </span>
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
