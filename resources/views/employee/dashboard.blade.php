@extends('layouts.app')
@section('title', 'Dashboard Karyawan')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">

    <div class="bg-gradient-to-r from-white to-mna-light rounded-3xl p-6 sm:p-8 shadow-card border border-teal-50 flex flex-col md:flex-row justify-between items-center gap-6 relative overflow-hidden">
        <div class="absolute right-0 top-0 w-64 h-64 bg-mna-teal/5 rounded-full blur-3xl -mr-20 -mt-20"></div>
        
        <div class="flex items-center gap-5 z-10">
            <div class="relative group">
                <a href="{{ route('profile.edit') }}" class="block h-20 w-20 rounded-full overflow-hidden shadow-md border-4 border-white bg-white transition-transform duration-300 group-hover:scale-105">
                    @if(Auth::user()->profile_photo)
                        <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" alt="Profile" class="h-full w-full object-cover">
                    @else
                        <div class="h-full w-full bg-gradient-to-br from-mna-teal to-mna-dark text-white flex items-center justify-center text-3xl font-extrabold">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <i class="fas fa-camera text-white text-xl"></i>
                    </div>
                </a>
                <div class="absolute -bottom-2 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-[10px] px-2 py-0.5 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">
                    Ubah Foto
                </div>
            </div>

            <div>
                <p class="text-sm font-bold text-mna-teal uppercase tracking-widest mb-1">Selamat Datang Kembali,</p>
                <h2 class="text-2xl font-extrabold text-gray-800 leading-tight">{{ Auth::user()->name }}!</h2>
                <p class="text-sm text-gray-500 font-medium mt-1 flex items-center gap-2">
                    <i class="fas fa-briefcase text-gray-400"></i> {{ Auth::user()->position ?? 'Staff' }} 
                    <span class="text-gray-300">|</span> 
                    <i class="fas fa-building text-gray-400"></i> {{ Auth::user()->department->name ?? 'Umum' }}
                </p>
            </div>
        </div>
        
        <a href="{{ route('employee.permit.create') }}" class="z-10 group relative inline-flex items-center justify-center px-6 py-3.5 text-sm font-bold text-white transition-all duration-300 bg-mna-teal rounded-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-mna-teal hover:bg-mna-dark shadow-lg shadow-mna-teal/30 hover:-translate-y-1">
            <svg class="w-5 h-5 mr-2 -ml-1 transition-transform group-hover:rotate-90" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Ajukan Izin Keluar
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-2xl shadow-card border border-gray-100 relative overflow-hidden group hover:-translate-y-1 transition-all duration-300">
            <div class="absolute right-0 top-0 h-24 w-24 bg-yellow-50 rounded-full -mr-6 -mt-6 transition-transform duration-500 group-hover:scale-150"></div>
            <div class="relative z-10 flex justify-between items-start">
                <div>
                    <p class="text-[11px] font-extrabold text-gray-400 uppercase tracking-wider mb-1">Menunggu Approval</p>
                    <div class="flex items-end gap-2">
                        <h3 class="text-4xl font-black text-gray-800">{{ $stats['pending'] }}</h3>
                        <span class="text-sm text-yellow-600 font-bold mb-1.5">Permohonan</span>
                    </div>
                </div>
                <div class="h-10 w-10 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-600">
                    <i class="fas fa-clock text-lg"></i>
                </div>
            </div>
            <div class="mt-5 h-1.5 w-full bg-gray-50 rounded-full overflow-hidden">
                <div class="h-full bg-yellow-400 w-1/2 rounded-full"></div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-card border border-gray-100 relative overflow-hidden group hover:-translate-y-1 transition-all duration-300">
            <div class="absolute right-0 top-0 h-24 w-24 bg-green-50 rounded-full -mr-6 -mt-6 transition-transform duration-500 group-hover:scale-150"></div>
            <div class="relative z-10 flex justify-between items-start">
                <div>
                    <p class="text-[11px] font-extrabold text-gray-400 uppercase tracking-wider mb-1">Disetujui Bulan Ini</p>
                    <div class="flex items-end gap-2">
                        <h3 class="text-4xl font-black text-gray-800">{{ $stats['approved'] }}</h3>
                        <span class="text-sm text-green-600 font-bold mb-1.5">Kali Izin</span>
                    </div>
                </div>
                <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                    <i class="fas fa-check-double text-lg"></i>
                </div>
            </div>
            <div class="mt-5 h-1.5 w-full bg-gray-50 rounded-full overflow-hidden">
                <div class="h-full bg-green-500 w-3/4 rounded-full"></div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-card border border-gray-100 relative overflow-hidden group hover:-translate-y-1 transition-all duration-300">
            <div class="absolute right-0 top-0 h-24 w-24 bg-blue-50 rounded-full -mr-6 -mt-6 transition-transform duration-500 group-hover:scale-150"></div>
            <div class="relative z-10 flex justify-between items-start">
                <div>
                    <p class="text-[11px] font-extrabold text-gray-400 uppercase tracking-wider mb-1">Total Riwayat</p>
                    <div class="flex items-end gap-2">
                        <h3 class="text-4xl font-black text-gray-800">{{ $stats['total'] }}</h3>
                        <span class="text-sm text-blue-600 font-bold mb-1.5">Seluruh Waktu</span>
                    </div>
                </div>
                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                    <i class="fas fa-folder-open text-lg"></i>
                </div>
            </div>
            <div class="mt-5 h-1.5 w-full bg-gray-50 rounded-full overflow-hidden">
                <div class="h-full bg-blue-500 w-full rounded-full"></div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-card border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-white">
            <h3 class="font-extrabold text-gray-800 text-lg flex items-center gap-2">
                <i class="fas fa-list text-mna-teal"></i> Aktivitas Terkini
            </h3>
            <a href="{{ route('employee.my-permits') }}" class="text-sm text-mna-teal font-bold hover:text-mna-dark transition-colors bg-mna-light px-4 py-2 rounded-lg">
                Lihat Semua <i class="fas fa-arrow-right ml-1 text-xs"></i>
            </a>
        </div>
        
        <div class="p-6 overflow-x-auto">
            <table class="datatable w-full text-left">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Keperluan</th>
                        <th>Alasan</th>
                        <th>Status</th>
                        <th class="text-right">Jam Keluar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentPermits as $permit)
                    <tr>
                        <td>
                            <div class="font-bold text-gray-800">{{ $permit->created_at->format('d M Y') }}</div>
                            <div class="text-xs font-semibold text-gray-400 mt-0.5">{{ $permit->created_at->format('H:i') }} WIB</div>
                        </td>
                        <td>
                            @if($permit->permit_type == 'tugas')
                                <span class="text-blue-700 font-bold text-xs bg-blue-50 border border-blue-100 px-2.5 py-1 rounded-md flex items-center w-max gap-1">
                                    <i class="fas fa-briefcase"></i> Tugas Kantor
                                </span>
                            @else
                                <span class="text-purple-700 font-bold text-xs bg-purple-50 border border-purple-100 px-2.5 py-1 rounded-md flex items-center w-max gap-1">
                                    <i class="fas fa-user"></i> Pribadi
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="text-gray-600 font-medium max-w-xs truncate" title="{{ $permit->reason }}">
                                {{ $permit->reason }}
                            </div>
                        </td>
                        <td>
                            @if($permit->status == 'pending')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-yellow-50 text-yellow-700 border border-yellow-200">
                                    <span class="w-2 h-2 mr-1.5 bg-yellow-400 rounded-full animate-pulse"></span> Menunggu HOD
                                </span>
                            @elseif($permit->status == 'approved')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-50 text-green-700 border border-green-200">
                                    <i class="fas fa-check mr-1.5"></i> Disetujui
                                </span>
                            @elseif($permit->status == 'rejected')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-50 text-red-700 border border-red-200">
                                    <i class="fas fa-times mr-1.5"></i> Ditolak
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gray-50 text-gray-700 border border-gray-200">
                                    {{ ucfirst($permit->status) }}
                                </span>
                            @endif
                        </td>
                        <td class="text-right font-mono text-gray-700 font-bold">
                            @if($permit->time_out)
                                <span class="bg-gray-100 px-2 py-1 rounded text-sm">{{ \Carbon\Carbon::parse($permit->time_out)->format('H:i') }}</span>
                            @else
                                <span class="text-gray-300">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection