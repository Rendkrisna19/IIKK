<div @click="openDetail({{ $log }})" class="group flex items-center justify-between p-3 rounded-xl bg-white border border-gray-100 shadow-sm hover:shadow-md transition-all cursor-pointer active:scale-95">
    <div class="flex items-center gap-3">
        <div class="w-9 h-9 rounded-full flex items-center justify-center {{ $log->status == 'out' ? 'bg-red-50 text-red-500' : 'bg-green-50 text-green-500' }}">
            @if($log->status == 'out')
                <svg class="w-4 h-4 transform rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
            @else
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
            @endif
        </div>
        <div class="flex flex-col">
            <span class="text-xs font-bold text-gray-800 leading-tight">{{ Str::limit($log->user->name, 18) }}</span>
            <div class="flex items-center gap-1.5 mt-0.5">
                <span class="text-[9px] px-1.5 py-0.5 rounded {{ $log->permit_type == 'tugas' ? 'bg-blue-100 text-blue-700' : 'bg-orange-100 text-orange-700' }} font-semibold">
                    {{ $log->permit_type == 'tugas' ? 'TUGAS' : 'PRIBADI' }}
                </span>
                <span class="text-[9px] text-gray-400 font-mono font-semibold">
                    {{ $log->status == 'out' ? \Carbon\Carbon::parse($log->time_out)->format('H:i') : \Carbon\Carbon::parse($log->time_in)->format('H:i') }}
                </span>
            </div>
        </div>
    </div>
</div>