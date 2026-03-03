@extends('layouts.app')
@section('title', 'Riwayat Departemen')

@section('content')
<div class="max-w-7xl mx-auto pb-10">

    <div class="flex flex-col md:flex-row justify-between items-end mb-6 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Arsip Izin Departemen</h2>
            <p class="text-gray-500 mt-1">Rekap data izin keluar masuk karyawan di unit Anda.</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-200">
                    <tr>
                        <th class="p-5 font-semibold">Karyawan & No. Izin</th>
                        <th class="p-5 font-semibold">Waktu Pengajuan</th>
                        <th class="p-5 font-semibold">Keperluan</th>
                        <th class="p-5 font-semibold">Status</th>
                        <th class="p-5 font-semibold">Approval Oleh</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($permits as $permit)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="p-5">
                            <div class="font-bold text-gray-800 text-base">{{ $permit->user->name }}</div>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-xs text-gray-400 font-mono" title="NIK Karyawan">NIK: {{ $permit->user->nik ?? '-' }}</span>
                                <span class="text-gray-300">|</span>
                                <span class="text-xs font-bold text-mna-teal font-mono bg-mna-teal/10 px-2 py-0.5 rounded" title="Nomor Izin Unik">
                                    {{ $permit->unique_code ?? 'Draft' }}
                                </span>
                            </div>
                        </td>
                        <td class="p-5">
                            <div class="text-gray-700 font-medium">{{ $permit->created_at->format('d M Y') }}</div>
                            <div class="text-xs text-gray-400">{{ $permit->created_at->format('H:i') }} WIB</div>
                        </td>
                        <td class="p-5">
                            <div class="mb-1">
                                @if($permit->permit_type == 'tugas')
                                    <span class="text-[10px] font-bold bg-mna-teal/10 text-mna-dark border border-mna-teal/20 px-2 py-0.5 rounded">TUGAS</span>
                                @else
                                    <span class="text-[10px] font-bold bg-orange-100 text-orange-700 border border-orange-200 px-2 py-0.5 rounded">PRIBADI</span>
                                @endif
                            </div>
                            <p class="text-gray-600 truncate max-w-xs text-xs italic mt-1.5" title="{{ $permit->reason }}">"{{ $permit->reason }}"</p>
                        </td>
                        <td class="p-5">
                            @if($permit->status == 'approved')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">
                                    Disetujui
                                </span>
                            @elseif($permit->status == 'rejected')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">
                                    Ditolak
                                </span>
                            @elseif($permit->status == 'out')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700">
                                    Sedang Keluar
                                </span>
                            @elseif($permit->status == 'returned')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-700 border border-gray-200">
                                    Selesai
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700">
                                    Pending
                                </span>
                            @endif
                        </td>
                        <td class="p-5">
                            @if($permit->approver)
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center text-xs font-bold border border-gray-300">
                                        {{ substr($permit->approver->name, 0, 1) }}
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-gray-700 font-semibold text-xs">{{ $permit->approver->name }}</span>
                                        <span class="text-[10px] text-gray-400">
                                            {{ $permit->approved_at ? \Carbon\Carbon::parse($permit->approved_at)->format('d/m/Y H:i') : '-' }}
                                        </span>
                                    </div>
                                </div>
                            @else
                                <span class="text-xs text-gray-400 italic">Menunggu...</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-10 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-400">
                                <svg class="w-12 h-12 mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <p>Belum ada riwayat pengajuan izin di departemen Anda.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($permits->hasPages())
        <div class="p-4 border-t border-gray-100 bg-gray-50/50">
            {{ $permits->links() }}
        </div>
        @endif
    </div>

</div>
@endsection