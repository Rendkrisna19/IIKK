<header class="bg-gradient-to-br from-mna-dark to-mna-teal text-white p-5 pb-8 rounded-b-[2rem] shadow-xl relative z-10 shrink-0">
    <div class="flex justify-between items-center mb-5">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center border border-white/10 overflow-hidden shadow-sm">
                @if(Auth::user()->profile_photo)
                    <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" class="w-full h-full object-cover">
                @else
                    <span class="font-bold text-sm">{{ substr(Auth::user()->name, 0, 1) }}</span>
                @endif
            </div>
            <div>
                <h1 class="text-sm font-bold leading-tight">{{ Auth::user()->name }}</h1>
                <p class="text-[10px] text-mna-accent font-medium tracking-wider uppercase">Security Post</p>
            </div>
        </div>
        
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="bg-white/10 p-2 rounded-xl hover:bg-red-500/80 transition text-white/80 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
            </button>
        </form>
    </div>

    <div class="grid grid-cols-3 gap-2">
        <div class="bg-white/10 backdrop-blur-md p-2.5 rounded-xl border border-white/10 flex flex-col items-center">
            <span class="text-xl font-bold">{{ $stats['out'] }}</span>
            <span class="text-[9px] uppercase opacity-70 mt-0.5">Keluar</span>
        </div>
        <div class="bg-mna-accent/20 backdrop-blur-md p-2.5 rounded-xl border border-mna-accent/30 flex flex-col items-center relative overflow-hidden">
            <div class="absolute inset-0 bg-yellow-400/10 animate-pulse"></div>
            <span class="text-xl font-bold text-yellow-300">{{ $stats['active_outside'] }}</span>
            <span class="text-[9px] uppercase text-yellow-100 mt-0.5">Active</span>
        </div>
        <div class="bg-white/10 backdrop-blur-md p-2.5 rounded-xl border border-white/10 flex flex-col items-center">
            <span class="text-xl font-bold">{{ $stats['in'] }}</span>
            <span class="text-[9px] uppercase opacity-70 mt-0.5">Masuk</span>
        </div>
    </div>
</header>