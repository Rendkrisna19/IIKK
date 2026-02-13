@extends('layouts.app')
@section('title', 'Riwayat Departemen')

@section('content')
<div class="max-w-7xl mx-auto">

    <div class="flex flex-col md:flex-row justify-between items-end mb-6 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Arsip Izin Departemen</h2>
            <p class="text-gray-500">Rekap data izin keluar masuk karyawan di unit Anda.</p>
        </div>
        </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-200">
                    <tr>
                        <th class="p-5 font-semibold">Karyawan</th>
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
                            <div class="font-bold text-gray-800">{{ $permit->user->name }}</div>
                            <div class="text-xs text-gray-400">{{ $permit->user->nik }}</div>
                        </td>
                        <td class="p-5">
                            <div class="text-gray-700">{{ $permit->created_at->format('d M Y') }}</div>
                            <div class="text-xs text-gray-400">{{ $permit->created_at->format('H:i') }} WIB</div>
                        </td>
                        <td class="p-5">
                            <div class="mb-1">
                                @if($permit->permit_type == 'tugas')
                                    <span class="text-[10px] font-bold bg-blue-100 text-blue-700 px-2 py-0.5 rounded">TUGAS</span>
                                @else
                                    <span class="text-[10px] font-bold bg-orange-100 text-orange-700 px-2 py-0.5 rounded">PRIBADI</span>
                                @endif
                            </div>
                            <p class="text-gray-600 truncate max-w-xs italic">"{{ $permit->reason }}"</p>
                        </td>
                        <td class="p-5">
                            @if($permit->status == 'approved')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700">
                                    Disetujui
                                </span>
                            @elseif($permit->status == 'rejected')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-700">
                                    Ditolak
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-100 text-gray-700">
                                    {{ ucfirst($permit->status) }}
                                </span>
                            @endif
                        </td>
                        <td class="p-5">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center text-[10px] font-bold">
                                    {{ substr($permit->approver->name ?? '-', 0, 1) }}
                                </div>
                                <span class="text-gray-600 text-xs">{{ $permit->approver->name ?? '-' }}</span>
                            </div>
                            <div class="text-[10px] text-gray-400 mt-0.5 pl-8">
                                {{ $permit->approved_at ? \Carbon\Carbon::parse($permit->approved_at)->format('d/m H:i') : '-' }}
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-8 text-center text-gray-400">
                            Belum ada riwayat data izin.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-4 border-t border-gray-100">
            {{ $permits->links() }}
        </div>
    </div>

</div>
@endsection