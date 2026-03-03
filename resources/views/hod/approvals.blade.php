@extends('layouts.app')
@section('title', 'Persetujuan Izin')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

@section('content')
<div class="max-w-7xl mx-auto" x-data="{ actionModal: false, actionType: '', permitId: '', actionText: '', actionColor: '' }">

    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-800">Antrean Menunggu Persetujuan</h2>
        <p class="text-gray-500">Tinjau permohonan izin karyawan departemen Anda.</p>
    </div>

    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
             class="fixed top-5 right-5 z-50 bg-mna-dark text-white px-6 py-4 rounded-xl shadow-2xl flex items-center animate-bounce-in">
            <i class="fa-solid fa-check mr-3"></i>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif

    @if($permits->isEmpty())
        <div class="bg-white rounded-2xl p-12 text-center border border-dashed border-gray-300">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-green-50 rounded-full mb-4">
                <i class="fa-solid fa-check-double text-4xl text-green-500"></i>
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
                        <span class="bg-blue-100 text-blue-700 text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wide">
                            <i class="fa-solid fa-briefcase mr-1"></i> Tugas
                        </span>
                    @else
                        <span class="bg-orange-100 text-orange-700 text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wide">
                            <i class="fa-solid fa-person-walking-arrow-right mr-1"></i> Pribadi
                        </span>
                    @endif
                </div>

                <div class="p-6 flex-1">
                    <p class="text-xs text-gray-400 font-bold uppercase mb-2">Alasan / Keperluan</p>
                    <p class="text-gray-700 text-sm leading-relaxed italic bg-gray-50 p-3 rounded-lg border border-gray-100">
                        "{{ $permit->reason }}"
                    </p>
                    
                    <div class="mt-4 flex items-center gap-2 text-xs text-gray-400">
                        <i class="fa-solid fa-clock"></i>
                        Diajukan {{ $permit->created_at->diffForHumans() }}
                    </div>
                </div>

                <div class="p-4 bg-gray-50 border-t border-gray-100 grid grid-cols-2 gap-3">
                    <button @click="actionModal = true; actionType = 'rejected'; permitId = '{{ $permit->id }}'; actionText = 'Tolak Izin'; actionColor = 'bg-red-600 hover:bg-red-700'" 
                            class="w-full py-2.5 rounded-xl border border-red-200 text-red-600 font-bold text-sm hover:bg-red-50 transition-colors flex items-center justify-center">
                        <i class="fa-solid fa-xmark mr-2"></i> Tolak
                    </button>

                    <button @click="actionModal = true; actionType = 'approved'; permitId = '{{ $permit->id }}'; actionText = 'Setujui Izin'; actionColor = 'bg-mna-dark hover:bg-mna-teal'" 
                            class="w-full py-2.5 rounded-xl bg-mna-dark text-white font-bold text-sm hover:bg-mna-teal shadow-lg shadow-mna-teal/20 transition-all flex items-center justify-center">
                        <i class="fa-solid fa-check mr-2"></i> Setujui
                    </button>
                </div>

            </div>
            @endforeach
        </div>
    @endif

    <div x-show="actionModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div @click.away="actionModal = false" class="bg-white rounded-2xl w-full max-w-md p-6 shadow-2xl relative transform transition-all">
            
            <h3 class="text-xl font-bold mb-2 text-gray-800">
                <i class="fa-solid fa-clipboard-check text-mna-teal mr-2"></i> Konfirmasi Pengajuan
            </h3>
            <p class="text-sm text-gray-500 mb-6">Berikan pesan atau catatan untuk karyawan (Opsional).</p>
            
            <form :action="'{{ url('hod/permit') }}/' + permitId + '/update'" method="POST">
                @csrf
                @method('PATCH')
                
                <input type="hidden" name="status" :value="actionType">
                
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Pesan HOD</label>
                    <textarea name="hod_message" rows="3" class="w-full rounded-xl border-gray-200 bg-gray-50 p-3 text-sm focus:bg-white focus:ring-2 focus:ring-mna-teal outline-none transition" placeholder="Contoh: Pekerjaan tolong di-handover ke Budi dulu ya..."></textarea>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" @click="actionModal = false" class="px-5 py-2.5 rounded-xl bg-gray-100 text-gray-600 font-bold hover:bg-gray-200 text-sm transition">Batal</button>
                    <button type="submit" :class="actionColor" class="px-5 py-2.5 rounded-xl text-white font-bold text-sm transition flex items-center gap-2">
                        <span x-text="actionText"></span>
                    </button>
                </div>
            </form>

        </div>
    </div>

</div>
@endsection