@extends('layouts.app')
@section('title', 'Dashboard Karyawan')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col md:flex-row justify-between items-center gap-6">
        <div class="flex items-center gap-4">
            <div class="h-16 w-16 rounded-full bg-mna-teal text-white flex items-center justify-center text-2xl font-bold shadow-md">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-800">Halo, {{ Auth::user()->name }}!</h2>
                <p class="text-sm text-gray-500">{{ Auth::user()->position ?? 'Staff' }} - {{ Auth::user()->department->name ?? 'Umum' }}</p>
            </div>
        </div>
        
        <a href="{{ route('employee.permit.create') }}" class="group relative inline-flex items-center justify-center px-6 py-3 text-sm font-bold text-white transition-all duration-200 bg-mna-dark font-pj rounded-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-mna-dark hover:bg-mna-teal shadow-lg shadow-mna-teal/30">
            <svg class="w-5 h-5 mr-2 -ml-1 transition-transform group-hover:rotate-90" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Ajukan Izin Keluar
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden group hover:-translate-y-1 transition-all">
            <div class="absolute right-0 top-0 h-24 w-24 bg-yellow-50 rounded-full -mr-6 -mt-6 transition-transform group-hover:scale-110"></div>
            <div class="relative z-10">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Menunggu Approval</p>
                <div class="flex items-end gap-2">
                    <h3 class="text-3xl font-bold text-gray-800">{{ $stats['pending'] }}</h3>
                    <span class="text-sm text-yellow-600 font-medium mb-1">Permohonan</span>
                </div>
            </div>
            <div class="mt-4 h-1 w-full bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full bg-yellow-400 w-1/2 rounded-full"></div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden group hover:-translate-y-1 transition-all">
            <div class="absolute right-0 top-0 h-24 w-24 bg-green-50 rounded-full -mr-6 -mt-6 transition-transform group-hover:scale-110"></div>
            <div class="relative z-10">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Disetujui Bulan Ini</p>
                <div class="flex items-end gap-2">
                    <h3 class="text-3xl font-bold text-gray-800">{{ $stats['approved'] }}</h3>
                    <span class="text-sm text-green-600 font-medium mb-1">Kali Izin</span>
                </div>
            </div>
            <div class="mt-4 h-1 w-full bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full bg-green-500 w-3/4 rounded-full"></div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden group hover:-translate-y-1 transition-all">
            <div class="absolute right-0 top-0 h-24 w-24 bg-blue-50 rounded-full -mr-6 -mt-6 transition-transform group-hover:scale-110"></div>
            <div class="relative z-10">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Total Riwayat</p>
                <div class="flex items-end gap-2">
                    <h3 class="text-3xl font-bold text-gray-800">{{ $stats['total'] }}</h3>
                    <span class="text-sm text-blue-600 font-medium mb-1">Seluruh Waktu</span>
                </div>
            </div>
            <div class="mt-4 h-1 w-full bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full bg-blue-500 w-full rounded-full"></div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h3 class="font-bold text-gray-800">Aktivitas Terkini</h3>
            <a href="{{ route('employee.my-permits') }}" class="text-sm text-mna-teal font-semibold hover:underline">Lihat Semua</a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="p-4 font-semibold">Tanggal</th>
                        <th class="p-4 font-semibold">Keperluan</th>
                        <th class="p-4 font-semibold">Alasan</th>
                        <th class="p-4 font-semibold">Status</th>
                        <th class="p-4 font-semibold text-right">Jam Keluar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($recentPermits as $permit)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="p-4 font-medium text-gray-700">
                            {{ $permit->created_at->format('d M Y') }}
                            <div class="text-xs text-gray-400">{{ $permit->created_at->format('H:i') }} WIB</div>
                        </td>
                        <td class="p-4">
                            @if($permit->permit_type == 'tugas')
                                <span class="text-blue-600 font-bold text-xs bg-blue-50 px-2 py-1 rounded">Tugas Kantor</span>
                            @else
                                <span class="text-purple-600 font-bold text-xs bg-purple-50 px-2 py-1 rounded">Pribadi</span>
                            @endif
                        </td>
                        <td class="p-4 text-gray-600 max-w-xs truncate" title="{{ $permit->reason }}">
                            {{ $permit->reason }}
                        </td>
                        <td class="p-4">
                            @if($permit->status == 'pending')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <span class="w-2 h-2 mr-1 bg-yellow-400 rounded-full animate-pulse"></span> Menunggu HOD
                                </span>
                            @elseif($permit->status == 'approved')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Disetujui
                                </span>
                            @elseif($permit->status == 'rejected')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Ditolak
                                </span>
                            @endif
                        </td>
                        <td class="p-4 text-right font-mono text-gray-600">
                            {{ $permit->time_out ? \Carbon\Carbon::parse($permit->time_out)->format('H:i') : '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-8 text-center text-gray-400">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <p>Belum ada riwayat pengajuan izin.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection