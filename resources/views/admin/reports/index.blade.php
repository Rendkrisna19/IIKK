@extends('layouts.app')
@section('title', 'Laporan & Rekapitulasi')

@section('content')
<div class="max-w-7xl mx-auto">

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-8">
        <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-mna-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
            Filter Data Laporan
        </h3>
        
        <form action="{{ route('admin.reports.index') }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="text-xs font-bold text-gray-500">Tanggal Mulai</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" required class="w-full border-gray-300 rounded-lg text-sm">
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-500">Tanggal Selesai</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" required class="w-full border-gray-300 rounded-lg text-sm">
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-500">Departemen</label>
                    <select name="department_id" class="w-full border-gray-300 rounded-lg text-sm">
                        <option value="">Semua Departemen</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-500">Status</label>
                    <select name="status" class="w-full border-gray-300 rounded-lg text-sm">
                        <option value="">Semua Status</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                        <option value="out" {{ request('status') == 'out' ? 'selected' : '' }}>Sedang Keluar</option>
                        <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
            </div>
            
            <div class="mt-4 flex gap-3">
                <button type="submit" class="bg-mna-dark text-white px-6 py-2 rounded-lg text-sm font-bold hover:bg-mna-teal transition">
                    Tampilkan Data
                </button>
                @if(request('start_date'))
                    <a href="{{ route('admin.reports.excel', request()->all()) }}" class="bg-green-600 text-white px-6 py-2 rounded-lg text-sm font-bold hover:bg-green-700 transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Export Excel
                    </a>
                    <a href="{{ route('admin.reports.pdf', request()->all()) }}" target="_blank" class="bg-red-600 text-white px-6 py-2 rounded-lg text-sm font-bold hover:bg-red-700 transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        Cetak PDF
                    </a>
                @endif
            </div>
        </form>
    </div>

    @if(count($permits) > 0)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                <tr>
                    <th class="p-4">Tanggal</th>
                    <th class="p-4">Karyawan</th>
                    <th class="p-4">Keperluan</th>
                    <th class="p-4">Status</th>
                    <th class="p-4">Waktu</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @foreach($permits as $permit)
                <tr class="hover:bg-gray-50">
                    <td class="p-4 font-mono">{{ $permit->created_at->format('d/m/Y') }}</td>
                    <td class="p-4">
                        <div class="font-bold">{{ $permit->user->name }}</div>
                        <div class="text-xs text-gray-500">{{ $permit->user->department->name ?? '-' }}</div>
                    </td>
                    <td class="p-4">{{ $permit->reason }}</td>
                    <td class="p-4">
                        <span class="px-2 py-1 rounded text-xs font-bold {{ $permit->status == 'approved' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                            {{ ucfirst($permit->status) }}
                        </span>
                    </td>
                    <td class="p-4 text-xs">
                        <div>Out: {{ $permit->time_out ? \Carbon\Carbon::parse($permit->time_out)->format('H:i') : '-' }}</div>
                        <div>In : {{ $permit->time_in ? \Carbon\Carbon::parse($permit->time_in)->format('H:i') : '-' }}</div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @elseif(request('start_date'))
        <div class="text-center p-8 bg-white rounded-2xl border border-gray-100 text-gray-500">
            Tidak ada data ditemukan untuk filter ini.
        </div>
    @endif

</div>
@endsection