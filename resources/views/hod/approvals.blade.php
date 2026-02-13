@extends('layouts.app')
@section('title', 'Persetujuan Izin')

@section('content')
<div class="max-w-7xl mx-auto">

    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-800">Antrean Menunggu Persetujuan</h2>
        <p class="text-gray-500">Tinjau permohonan izin karyawan departemen Anda.</p>
    </div>

    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
             class="fixed top-5 right-5 z-50 bg-mna-dark text-white px-6 py-4 rounded-xl shadow-2xl flex items-center animate-bounce-in">
            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif

    @if($permits->isEmpty())
        <div class="bg-white rounded-2xl p-12 text-center border border-dashed border-gray-300">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-green-50 rounded-full mb-4">
                <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h3 class="text-xl font-bold text-gray-800">Semua Beres!</h3>
            <p class="text-gray-500">Tidak ada permohonan izin yang menunggu persetujuan saat ini.</p>
            <a href="{{ route('dashboard') }}" class="inline-block mt-4 text-mna-teal font-bold hover:underline">Kembali ke Dashboard</a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($permits as $permit)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col hover:shadow-lg transition-shadow duration-300">
                
                <div class="p-6 border-b border-gray-50 flex items-start justify-between bg-gradient-to-r from-gray-50 to-white">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-mna-teal text-white flex items-center justify-center font-bold text-sm">
                            {{ substr($permit->user->name, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800 text-sm">{{ $permit->user->name }}</h3>
                            <p class="text-xs text-gray-500">{{ $permit->user->position ?? 'Staff' }}</p>
                        </div>
                    </div>
                    @if($permit->permit_type == 'tugas')
                        <span class="bg-blue-100 text-blue-700 text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wide">Tugas</span>
                    @else
                        <span class="bg-orange-100 text-orange-700 text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wide">Pribadi</span>
                    @endif
                </div>

                <div class="p-6 flex-1">
                    <p class="text-xs text-gray-400 font-bold uppercase mb-2">Alasan / Keperluan</p>
                    <p class="text-gray-700 text-sm leading-relaxed italic bg-gray-50 p-3 rounded-lg border border-gray-100">
                        "{{ $permit->reason }}"
                    </p>
                    
                    <div class="mt-4 flex items-center gap-2 text-xs text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Diajukan {{ $permit->created_at->diffForHumans() }}
                    </div>
                </div>

                <div class="p-4 bg-gray-50 border-t border-gray-100 grid grid-cols-2 gap-3">
                    <form action="{{ route('hod.permit.update', $permit->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menolak izin ini?')">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="rejected">
                        <button type="submit" class="w-full py-2.5 rounded-xl border border-red-200 text-red-600 font-bold text-sm hover:bg-red-50 transition-colors flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            Tolak
                        </button>
                    </form>

                    <form action="{{ route('hod.permit.update', $permit->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="approved">
                        <button type="submit" class="w-full py-2.5 rounded-xl bg-mna-dark text-white font-bold text-sm hover:bg-mna-teal shadow-lg shadow-mna-teal/20 transition-all flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Setujui
                        </button>
                    </form>
                </div>

            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection