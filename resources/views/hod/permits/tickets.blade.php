@extends('layouts.app')
@section('title', 'Tiket Izin Saya')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    .ticket-card { transition: all 0.3s cubic-bezier(.4,0,.2,1); }
    .ticket-card:hover { transform: translateY(-4px); box-shadow: 0 12px 40px -8px rgba(0,75,73,0.15); }
    .qr-glow { box-shadow: 0 0 20px -4px rgba(0,108,104,0.15); }
    .status-pulse { animation: statusPulse 2s ease-in-out infinite; }
    @keyframes statusPulse { 0%,100% { opacity: 1; } 50% { opacity: 0.7; } }
    .ticket-perforated { background-image: repeating-linear-gradient(0deg, transparent, transparent 8px, #e5e7eb 8px, #e5e7eb 9px); background-size: 1px 100%; background-position: left; background-repeat: no-repeat; }
</style>
@endpush

@section('content')
<div class="max-w-6xl mx-auto" x-data="{ qrModalOpen: false, activeUuid: '', activeName: '', activeReason: '', cancelModalOpen: false, cancelId: '' }">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h2 class="text-2xl font-extrabold text-gray-800 tracking-tight">Tiket Izin Saya</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola dan tampilkan QR Code izin pribadi Anda langsung di layar.</p>
        </div>
        <a href="{{ route('hod.permit.create') }}" class="bg-gradient-to-r from-mna-dark to-mna-teal text-white px-6 py-3 rounded-xl font-bold shadow-lg shadow-mna-teal/20 hover:shadow-xl hover:-translate-y-0.5 transition-all flex items-center gap-2 text-sm">
            <i class="fa-solid fa-plus"></i> Buat Izin Baru
        </a>
    </div>

    {{-- Quick Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        @php
            $totalPermits = $permits->count();
            $activePermits = $permits->whereIn('status', ['approved', 'out'])->count();
            $completedPermits = $permits->where('status', 'returned')->count();
            $cancelledPermits = $permits->whereIn('status', ['cancelled', 'expired'])->count();
        @endphp
        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
            <div class="text-2xl font-extrabold text-gray-800">{{ $totalPermits }}</div>
            <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mt-1">Total Izin</div>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-green-100 shadow-sm">
            <div class="text-2xl font-extrabold text-green-600">{{ $activePermits }}</div>
            <div class="text-xs font-bold text-green-500 uppercase tracking-wider mt-1">Aktif</div>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm">
            <div class="text-2xl font-extrabold text-gray-600">{{ $completedPermits }}</div>
            <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mt-1">Selesai</div>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-red-100 shadow-sm">
            <div class="text-2xl font-extrabold text-red-500">{{ $cancelledPermits }}</div>
            <div class="text-xs font-bold text-red-400 uppercase tracking-wider mt-1">Batal / Expired</div>
        </div>
    </div>

    {{-- Ticket Cards --}}
    @forelse($permits as $permit)
    <div class="ticket-card bg-white rounded-2xl border border-gray-100 shadow-sm mb-5 overflow-hidden relative">
        
        {{-- Top accent bar --}}
        <div class="h-1.5 w-full 
            @if(in_array($permit->status, ['approved','out'])) bg-gradient-to-r from-mna-dark to-mna-teal
            @elseif($permit->status == 'returned') bg-gradient-to-r from-gray-300 to-gray-400
            @elseif($permit->status == 'cancelled') bg-gradient-to-r from-red-400 to-red-500
            @elseif($permit->status == 'expired') bg-gradient-to-r from-gray-400 to-gray-500
            @elseif($permit->status == 'pending') bg-gradient-to-r from-yellow-400 to-orange-400
            @else bg-gradient-to-r from-red-400 to-red-600
            @endif"></div>

        <div class="flex flex-col lg:flex-row">
            
            {{-- Left Section: Details --}}
            <div class="flex-1 p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <span class="text-xs font-extrabold px-3 py-1 rounded-lg border
                                {{ $permit->permit_type == 'tugas' ? 'bg-blue-50 text-blue-700 border-blue-100' : 'bg-orange-50 text-orange-600 border-orange-100' }}">
                                <i class="fa-solid {{ $permit->permit_type == 'tugas' ? 'fa-briefcase' : 'fa-person-walking-arrow-right' }} mr-1"></i>
                                {{ ucfirst($permit->permit_type) }}
                            </span>
                            <span class="font-mono text-[10px] text-gray-400 bg-gray-50 px-2 py-1 rounded border border-gray-100">{{ $permit->unique_code }}</span>
                        </div>
                        <h3 class="text-base font-bold text-gray-800 leading-snug">{{ $permit->reason }}</h3>
                    </div>
                    
                    {{-- Status Badge --}}
                    <div class="shrink-0 ml-4">
                        @if($permit->status == 'approved')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-extrabold bg-green-50 text-green-700 border border-green-200 status-pulse">
                                <span class="w-2 h-2 rounded-full bg-green-500"></span> AKTIF
                            </span>
                        @elseif($permit->status == 'out')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-extrabold bg-blue-50 text-blue-700 border border-blue-200 animate-pulse">
                                <span class="w-2 h-2 rounded-full bg-blue-500"></span> DI LUAR
                            </span>
                        @elseif($permit->status == 'returned')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-extrabold bg-gray-100 text-gray-600 border border-gray-200">
                                <i class="fa-solid fa-check-double"></i> SELESAI
                            </span>
                        @elseif($permit->status == 'cancelled')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-extrabold bg-red-50 text-red-600 border border-red-200">
                                <i class="fa-solid fa-ban"></i> DIBATALKAN
                            </span>
                        @elseif($permit->status == 'expired')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-extrabold bg-gray-200 text-gray-700 border border-gray-300">
                                <i class="fa-solid fa-calendar-xmark"></i> KADALUARSA
                            </span>
                        @elseif($permit->status == 'pending')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-extrabold bg-yellow-50 text-yellow-700 border border-yellow-200">
                                <i class="fa-solid fa-clock"></i> MENUNGGU
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-extrabold bg-red-50 text-red-700 border border-red-200">
                                <i class="fa-solid fa-times-circle"></i> DITOLAK
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Info Grid --}}
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-4">
                    <div class="bg-gray-50 rounded-xl p-3 border border-gray-100">
                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Tanggal Izin</div>
                        <div class="text-sm font-bold text-gray-800">
                            {{ $permit->permit_date ? \Carbon\Carbon::parse($permit->permit_date)->format('d M Y') : '-' }}
                        </div>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-3 border border-gray-100">
                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Jam Keluar</div>
                        <div class="text-sm font-bold text-gray-800 font-mono">
                            {{ $permit->target_time_out ? \Carbon\Carbon::parse($permit->target_time_out)->format('H:i') : '-' }}
                        </div>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-3 border border-gray-100">
                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Jam Kembali</div>
                        <div class="text-sm font-bold text-gray-800 font-mono">
                            {{ $permit->target_time_in ? \Carbon\Carbon::parse($permit->target_time_in)->format('H:i') : '-' }}
                        </div>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-3 border border-gray-100">
                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Aktual</div>
                        <div class="text-xs font-bold text-gray-700 font-mono leading-relaxed">
                            <span class="{{ $permit->time_out ? 'text-blue-600' : 'text-gray-400' }}">Out: {{ $permit->time_out ? \Carbon\Carbon::parse($permit->time_out)->format('H:i') : '--:--' }}</span><br>
                            <span class="{{ $permit->time_in ? 'text-green-600' : 'text-gray-400' }}">In: {{ $permit->time_in ? \Carbon\Carbon::parse($permit->time_in)->format('H:i') : '--:--' }}</span>
                        </div>
                    </div>
                </div>

                @if($permit->cancel_message)
                    <div class="mt-3 bg-red-50 p-3 rounded-xl border border-red-100 text-xs text-red-600">
                        <span class="font-bold text-red-800 block mb-0.5"><i class="fa-solid fa-ban text-red-600 mr-1"></i> Alasan Batal:</span>
                        <p class="italic">"{{ $permit->cancel_message }}"</p>
                    </div>
                @endif

                {{-- Action Buttons --}}
                <div class="flex items-center gap-3 mt-5 pt-4 border-t border-gray-100">
                    @if(in_array($permit->status, ['approved', 'out']))
                        <button @click="qrModalOpen = true; activeUuid = '{{ $permit->uuid }}'; activeName = '{{ $permit->reason }}'; activeReason = '{{ $permit->unique_code }}'" 
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-mna-dark to-mna-teal text-white rounded-xl font-bold text-xs shadow-lg shadow-mna-teal/20 hover:shadow-xl hover:-translate-y-0.5 transition-all">
                            <i class="fa-solid fa-qrcode text-base"></i> Tampilkan QR
                        </button>
                        <a href="{{ route('hod.permit.print', $permit->id) }}" target="_blank"
                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl font-bold text-xs hover:bg-gray-50 hover:border-gray-300 transition-all shadow-sm">
                            <i class="fa-solid fa-print"></i> Cetak PDF
                        </a>
                    @endif
                    @if($permit->status == 'approved')
                        <button @click="cancelModalOpen = true; cancelId = '{{ $permit->id }}'" 
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-red-200 text-red-600 rounded-xl font-bold text-xs hover:bg-red-50 hover:border-red-300 transition-all shadow-sm">
                            <i class="fa-solid fa-ban"></i> Batalkan
                        </button>
                    @endif
                    @if(in_array($permit->status, ['returned', 'cancelled', 'expired', 'rejected']))
                        <span class="text-xs text-gray-400 italic"><i class="fa-solid fa-circle-check mr-1"></i> Tidak ada aksi tersedia</span>
                    @endif
                </div>
            </div>

            {{-- Right Section: Inline QR Code (only for active permits) --}}
            @if(in_array($permit->status, ['approved', 'out']))
            <div class="lg:w-56 shrink-0 flex flex-col items-center justify-center p-6 lg:border-l border-t lg:border-t-0 border-dashed border-gray-200 bg-gradient-to-b lg:bg-gradient-to-r from-gray-50/50 to-white relative">
                {{-- Decorative circles --}}
                <div class="hidden lg:block absolute -left-3.5 top-1/2 -translate-y-1/2 w-7 h-7 bg-[#F4F7F9] rounded-full border border-gray-100"></div>

                <div class="qr-glow bg-white p-3 rounded-2xl border border-gray-100 mb-3">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ $permit->uuid }}" 
                         alt="QR Code" class="w-36 h-36 rounded-lg">
                </div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">E-PASS</p>
                <p class="text-[9px] font-mono text-gray-300 mt-0.5 text-center break-all max-w-[160px]">{{ Str::limit($permit->uuid, 20) }}</p>
            </div>
            @endif
        </div>
    </div>
    @empty
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-16 text-center">
        <div class="w-20 h-20 mx-auto mb-4 bg-gray-50 rounded-full flex items-center justify-center">
            <i class="fa-solid fa-ticket text-3xl text-gray-300"></i>
        </div>
        <h3 class="text-lg font-bold text-gray-600 mb-2">Belum Ada Tiket Izin</h3>
        <p class="text-sm text-gray-400 mb-6">Buat izin pribadi untuk mendapatkan tiket dan QR Code.</p>
        <a href="{{ route('hod.permit.create') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-mna-dark to-mna-teal text-white px-6 py-3 rounded-xl font-bold text-sm shadow-lg shadow-mna-teal/20 hover:shadow-xl transition-all">
            <i class="fa-solid fa-plus"></i> Buat Izin Sekarang
        </a>
    </div>
    @endforelse

    {{-- QR Modal (Full Screen E-Pass) --}}
    <div x-show="qrModalOpen" style="display: none;" 
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div class="relative w-80" @click.away="qrModalOpen = false">
            <div class="bg-white rounded-3xl p-8 shadow-2xl transform transition-all duration-500 hover:scale-105 border-t-4 border-mna-teal text-center relative overflow-hidden">
                
                <div class="absolute top-0 right-0 w-32 h-32 bg-mna-light rounded-full -mr-10 -mt-10 opacity-50 blur-xl"></div>
                <div class="absolute bottom-0 left-0 w-24 h-24 bg-mna-teal rounded-full -ml-10 -mb-10 opacity-10 blur-xl"></div>

                <h3 class="text-xl font-bold text-mna-dark mb-1 relative z-10"><i class="fa-solid fa-id-badge mr-2"></i>E-PASS</h3>
                <p class="text-xs text-gray-400 mb-2 relative z-10">Tunjukkan ke Security untuk Scan</p>
                <p class="text-[10px] text-mna-teal font-bold mb-4 relative z-10 bg-mna-light px-3 py-1 rounded-lg inline-block" x-text="activeReason"></p>

                <div class="bg-white p-4 rounded-xl shadow-inner border border-gray-100 inline-block mb-4 relative z-10">
                    <div class="w-52 h-52 flex items-center justify-center bg-gray-50 rounded-lg">
                        <img :src="`https://api.qrserver.com/v1/create-qr-code/?size=240x240&data=${activeUuid}`" 
                             alt="QR Code" class="w-full h-full object-contain rounded-lg">
                    </div>
                </div>

                <p class="font-mono text-[10px] text-gray-400 mb-6 break-all bg-gray-50 p-2 rounded relative z-10" x-text="activeUuid"></p>

                <button @click="qrModalOpen = false" class="w-full py-3 rounded-xl bg-gray-100 text-gray-600 font-bold text-sm hover:bg-gray-200 hover:text-gray-800 transition-colors relative z-10">
                    <i class="fa-solid fa-xmark mr-1"></i> Tutup
                </button>
            </div>
        </div>
    </div>

    {{-- Cancel Modal --}}
    <div x-show="cancelModalOpen" style="display: none;" 
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div @click.away="cancelModalOpen = false" class="bg-white rounded-2xl w-full max-w-md p-6 shadow-2xl relative transform transition-all">
            
            <h3 class="text-xl font-bold mb-2 text-gray-800">
                <i class="fa-solid fa-ban text-red-500 mr-2"></i> Konfirmasi Pembatalan
            </h3>
            <p class="text-sm text-gray-500 mb-6">Silakan isi alasan mengapa izin ini dibatalkan.</p>
            
            <form :action="'{{ url('hod/permit') }}/' + cancelId + '/cancel'" method="POST">
                @csrf
                @method('PATCH')
                
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Alasan Pembatalan <span class="text-red-500">*</span></label>
                    <textarea name="cancel_message" rows="3" required class="w-full rounded-xl border-gray-200 bg-gray-50 p-3 text-sm focus:bg-white focus:ring-2 focus:ring-red-500 outline-none transition" placeholder="Contoh: Rencana berubah, tidak jadi keluar area kantor..."></textarea>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" @click="cancelModalOpen = false" class="px-5 py-2.5 rounded-xl bg-gray-100 text-gray-600 font-bold hover:bg-gray-200 text-sm transition">Tutup</button>
                    <button type="submit" class="bg-red-600 hover:bg-red-700 px-5 py-2.5 rounded-xl text-white font-bold text-sm transition flex items-center gap-2">
                        <i class="fa-solid fa-ban"></i> Batalkan Izin
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
