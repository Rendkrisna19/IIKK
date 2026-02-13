@extends('layouts.app')
@section('title', 'Dashboard Manajer')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">

    <div class="flex flex-col md:flex-row justify-between items-center bg-mna-dark text-white p-6 rounded-2xl shadow-lg relative overflow-hidden">
        <div class="relative z-10">
            <h2 class="text-2xl font-bold">Halo, {{ Auth::user()->name }}</h2>
            <p class="text-mna-light opacity-90 mt-1">Departemen: <strong>{{ Auth::user()->department->name }}</strong></p>
        </div>
        <div class="relative z-10 mt-4 md:mt-0 flex items-center gap-3">
            <div class="text-right">
                <p class="text-xs uppercase tracking-wider opacity-70">Perlu Persetujuan</p>
                <p class="text-3xl font-bold">{{ $stats['pending'] }}</p>
            </div>
            <div class="p-3 bg-white/20 rounded-lg">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
            </div>
        </div>
        <div class="absolute right-0 top-0 h-40 w-40 bg-white/10 rounded-full -mr-10 -mt-10 blur-2xl"></div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center">
            <div class="p-3 rounded-full bg-yellow-50 text-yellow-600 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase font-bold">Menunggu Anda</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['pending'] }} <span class="text-sm font-normal text-gray-400">Permohonan</span></p>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center">
            <div class="p-3 rounded-full bg-blue-50 text-blue-600 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase font-bold">Karyawan Izin Hari Ini</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['today_out'] }} <span class="text-sm font-normal text-gray-400">Orang</span></p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center">
            <div class="p-3 rounded-full bg-purple-50 text-purple-600 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase font-bold">Total Izin Bulan Ini</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['total_month'] }} <span class="text-sm font-normal text-gray-400">Total</span></p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h3 class="font-bold text-gray-800 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span>
                Antrean Persetujuan Terbaru
            </h3>
            <a href="{{ route('hod.approvals') }}" class="text-sm text-mna-teal font-bold hover:underline">Lihat Semua Antrean &rarr;</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="p-4">Karyawan</th>
                        <th class="p-4">Keperluan</th>
                        <th class="p-4">Alasan</th>
                        <th class="p-4">Waktu Request</th>
                        <th class="p-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($pendingPermits as $permit)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="p-4">
                            <p class="font-bold text-gray-800">{{ $permit->user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $permit->user->position ?? 'Staff' }}</p>
                        </td>
                        <td class="p-4">
                            @if($permit->permit_type == 'tugas')
                                <span class="bg-blue-100 text-blue-700 text-xs font-bold px-2 py-1 rounded">Tugas</span>
                            @else
                                <span class="bg-orange-100 text-orange-700 text-xs font-bold px-2 py-1 rounded">Pribadi</span>
                            @endif
                        </td>
                        <td class="p-4 text-gray-600 max-w-xs truncate">{{ $permit->reason }}</td>
                        <td class="p-4 text-gray-500 text-xs">
                            {{ $permit->created_at->diffForHumans() }}
                        </td>
                        <td class="p-4 text-right">
                            <a href="{{ route('hod.approvals') }}" class="inline-block bg-mna-dark text-white px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-mna-teal transition-colors">
                                Proses
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-8 text-center text-gray-400">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-200 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <p>Tidak ada permohonan yang menunggu persetujuan.</p>
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