<div @click="openDetail({{ $log }})" class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm active:bg-gray-50 transition cursor-pointer">
    <div class="flex justify-between items-start mb-2">
        <div>
            <h4 class="font-bold text-sm text-gray-900">{{ $log->user->name }}</h4>
            <p class="text-[10px] text-gray-500 font-medium">{{ $log->user->department->name ?? '-' }}</p>
        </div>
        <span class="text-[10px] font-mono font-bold bg-gray-100 px-2 py-1 rounded text-gray-600">{{ $log->created_at->format('d/m H:i') }}</span>
    </div>
    <div class="flex gap-2 mt-3">
        <div class="flex-1 bg-red-50 py-1.5 rounded text-center border border-red-100">
            <span class="block text-[9px] text-red-400 font-bold uppercase">Keluar</span>
            <span class="block text-sm font-black text-red-700 font-mono">{{ $log->time_out ? \Carbon\Carbon::parse($log->time_out)->format('H:i') : '-' }}</span>
        </div>
        <div class="flex-1 bg-green-50 py-1.5 rounded text-center border border-green-100">
            <span class="block text-[9px] text-green-400 font-bold uppercase">Masuk</span>
            <span class="block text-sm font-black text-green-700 font-mono">{{ $log->time_in ? \Carbon\Carbon::parse($log->time_in)->format('H:i') : '-' }}</span>
        </div>
    </div>
</div>