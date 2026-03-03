@extends('layouts.app')
@section('title', 'Riwayat & Dokumen')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

@section('content')
<div class="max-w-6xl mx-auto" x-data="{ qrModalOpen: false, activeQr: '', activeUuid: '' }">

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Dokumen Izin</h2>
        <a href="{{ route('employee.permit.create') }}" class="bg-mna-dark text-white px-5 py-2.5 rounded-xl font-bold shadow hover:bg-mna-teal transition flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Ajukan Baru
        </a>
    </div>

    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
             class="mb-6 bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-xl relative flex items-center gap-2 font-medium shadow-sm" role="alert">
            <i class="fa-solid fa-circle-check"></i>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase border-b">
                    <tr>
                        <th class="p-5">Tanggal Izin</th>
                        <th class="p-5">Keperluan</th>
                        <th class="p-5">Status</th>
                        <th class="p-5 text-right">Aksi / Dokumen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($permits as $permit)
                    <tr class="hover:bg-gray-50 transition">
                        
                        <td class="p-5">
                            <div class="font-bold text-gray-800">
                                {{ $permit->permit_date ? \Carbon\Carbon::parse($permit->permit_date)->format('d M Y') : $permit->created_at->format('d M Y') }}
                            </div>
                            <div class="text-xs text-gray-500 mt-1 flex items-center gap-1.5 font-mono">
                                <i class="fa-solid fa-clock text-mna-teal"></i>
                                {{ $permit->target_time_out ? \Carbon\Carbon::parse($permit->target_time_out)->format('H:i') : '-' }} 
                                @if($permit->permit_type == 'pribadi' && $permit->target_time_in)
                                    - {{ \Carbon\Carbon::parse($permit->target_time_in)->format('H:i') }}
                                @endif
                            </div>
                        </td>

                        <td class="p-5">
                            <span class="block font-medium text-gray-700 mb-1">{{ $permit->reason }}</span>
                            <span class="text-xs font-semibold px-2 py-1 rounded bg-gray-50 border border-gray-100 {{ $permit->permit_type == 'tugas' ? 'text-blue-600' : 'text-orange-600' }}">
                                <i class="fa-solid {{ $permit->permit_type == 'tugas' ? 'fa-briefcase' : 'fa-person-walking-arrow-right' }} mr-1"></i>
                                {{ ucfirst($permit->permit_type) }}
                            </span>
                        </td>

                        <td class="p-5">
                            @if($permit->status == 'approved')
                                <span class="bg-green-100 text-green-700 px-3 py-1.5 rounded-full text-xs font-bold inline-flex items-center gap-1">
                                    <i class="fa-solid fa-check-circle"></i> Disetujui
                                </span>
                            @elseif($permit->status == 'pending')
                                <span class="bg-yellow-100 text-yellow-700 px-3 py-1.5 rounded-full text-xs font-bold inline-flex items-center gap-1">
                                    <i class="fa-solid fa-clock"></i> Menunggu
                                </span>
                            @else
                                <span class="bg-red-100 text-red-700 px-3 py-1.5 rounded-full text-xs font-bold inline-flex items-center gap-1">
                                    <i class="fa-solid fa-times-circle"></i> Ditolak
                                </span>
                            @endif
                        </td>

                        <td class="p-5 text-right">
                            <div class="flex justify-end gap-3">
                                
                                @if($permit->status == 'approved')
                                    <button @click="qrModalOpen = true; activeQr = '{{ route('security.scan', $permit->uuid) }}'; activeUuid = '{{ $permit->uuid }}'" 
                                            class="group relative flex items-center justify-center w-10 h-10 rounded-lg bg-gray-100 text-gray-600 hover:bg-mna-dark hover:text-white transition-all shadow-sm" title="Tampilkan QR Code">
                                        <i class="fa-solid fa-qrcode text-lg"></i>
                                        <span class="absolute bottom-full mb-2 hidden group-hover:block text-xs bg-gray-800 text-white px-2 py-1 rounded whitespace-nowrap">Scan QR</span>
                                    </button>

                                    <a href="{{ route('employee.permit.print', $permit->id) }}" target="_blank"
                                       class="group relative flex items-center justify-center w-10 h-10 rounded-lg bg-gray-100 text-gray-600 hover:bg-mna-teal hover:text-white transition-all shadow-sm" title="Cetak Surat">
                                        <i class="fa-solid fa-print text-lg"></i>
                                        <span class="absolute bottom-full mb-2 hidden group-hover:block text-xs bg-gray-800 text-white px-2 py-1 rounded whitespace-nowrap">Cetak PDF</span>
                                    </a>
                                
                                @elseif($permit->status == 'pending')
                                    <a href="{{ route('employee.permit.edit', $permit->id) }}"
                                       class="group relative flex items-center justify-center w-10 h-10 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all shadow-sm" title="Edit Pengajuan">
                                        <i class="fa-solid fa-pen-to-square text-lg"></i>
                                        <span class="absolute bottom-full mb-2 hidden group-hover:block text-xs bg-gray-800 text-white px-2 py-1 rounded whitespace-nowrap">Edit</span>
                                    </a>

                                    <form action="{{ route('employee.permit.destroy', $permit->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan dan menghapus pengajuan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="group relative flex items-center justify-center w-10 h-10 rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all shadow-sm" title="Hapus Pengajuan">
                                            <i class="fa-solid fa-trash-can text-lg"></i>
                                            <span class="absolute bottom-full mb-2 hidden group-hover:block text-xs bg-gray-800 text-white px-2 py-1 rounded whitespace-nowrap">Hapus</span>
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-gray-400 italic">Tidak ada aksi</span>
                                @endif

                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-10 text-center text-gray-400">
                            <i class="fa-solid fa-folder-open text-4xl mb-3 text-gray-300 block"></i>
                            Belum ada pengajuan izin.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div x-show="qrModalOpen" style="display: none;" 
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div class="relative w-80 perspective-1000" @click.away="qrModalOpen = false">
            <div class="bg-white rounded-3xl p-8 shadow-2xl transform transition-all duration-500 hover:scale-105 border-t-4 border-mna-teal text-center relative overflow-hidden">
                
                <div class="absolute top-0 right-0 w-32 h-32 bg-mna-light rounded-full -mr-10 -mt-10 opacity-50 blur-xl"></div>
                <div class="absolute bottom-0 left-0 w-24 h-24 bg-mna-teal rounded-full -ml-10 -mb-10 opacity-10 blur-xl"></div>

                <h3 class="text-xl font-bold text-mna-dark mb-1 relative z-10"><i class="fa-solid fa-id-badge mr-2"></i>E-PASS</h3>
                <p class="text-xs text-gray-400 mb-6 relative z-10">Tunjukkan ke Security</p>

                <div class="bg-white p-4 rounded-xl shadow-inner border border-gray-100 inline-block mb-4 relative z-10">
                    <div class="w-48 h-48 flex items-center justify-center bg-gray-50 rounded-lg">
                        <img :src="`https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${activeUuid}`" 
                             alt="QR Code" class="w-full h-full object-contain rounded-lg">
                    </div>
                </div>

                <p class="font-mono text-[10px] text-gray-400 mb-6 break-all bg-gray-50 p-2 rounded" x-text="activeUuid"></p>

                <button @click="qrModalOpen = false" class="w-full py-3 rounded-xl bg-gray-100 text-gray-600 font-bold text-sm hover:bg-gray-200 hover:text-gray-800 transition-colors relative z-10">
                    <i class="fa-solid fa-xmark mr-1"></i> Tutup
                </button>
            </div>
        </div>
    </div>

</div>
@endsection