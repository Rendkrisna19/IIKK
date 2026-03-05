@extends('layouts.app')

@section('title', 'Histori & Laporan')

@push('styles')


<style>
    /* Global Font Overrides */
    body, div, span, p, table, input, select, button {
        font-family: 'Poppins', sans-serif !important;
    }

    :root {
        --sage-primary: #8BA888;
        --sage-dark: #5F7461;
        --sage-light: #F1F4F1;
        --mna-teal: #006C68;
        --pastel-red: #E57373;
        --pastel-red-hover: #EF5350;
    }

    /* CUSTOM DATATABLES STYLING */
    .dataTables_wrapper .dataTables_paginate {
        padding-top: 1.5rem !important;
        display: flex !important;
        justify-content: center !important;
        gap: 5px;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border: 1px solid #e2e8f0 !important;
        background: white !important;
        color: #64748b !important;
        border-radius: 10px !important;
        padding: 0.5rem 1rem !important;
        font-weight: 600 !important;
        transition: all 0.3s ease;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: var(--sage-light) !important;
        color: var(--sage-dark) !important;
        border-color: var(--sage-primary) !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: var(--mna-teal) !important;
        color: white !important;
        border-color: var(--mna-teal) !important;
        box-shadow: 0 4px 12px rgba(0, 108, 104, 0.2) !important;
    }

    .dataTables_wrapper .dataTables_info {
        text-align: center;
        margin-top: 1rem;
        font-size: 0.8rem;
        color: #94a3b8;
    }

    /* Hide Default Elements */
    .dataTables_length, .dataTables_filter { display: none !important; }
    table.dataTable.no-footer { border-bottom: none !important; }

    /* Table Aesthetic */
    #historyTable thead th {
        background-color: #F8FAFC;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 2px solid #F1F5F9 !important;
    }

    .custom-shadow {
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
    }

    /* Input & Select focus states */
    .focus-sage:focus {
        border-color: var(--sage-primary);
        box-shadow: 0 0 0 3px rgba(139, 168, 136, 0.2);
    }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto space-y-8 py-6 px-4">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-800 tracking-tight">Histori & <span class="text-mna-teal">Laporan</span></h2>
            <p class="text-gray-500 mt-2 font-medium">Manajemen data absensi dan izin karyawan secara terpusat.</p>
        </div>
        
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('hod.history.export.excel', ['month' => request('month')]) }}" 
               class="flex items-center gap-2 px-6 py-3 bg-white border-2 border-[#8BA888] text-[#5F7461] hover:bg-[#F1F4F1] text-sm font-bold rounded-2xl transition-all duration-300 transform hover:-translate-y-1">
                <i class="fa-solid fa-file-excel text-lg"></i> Export Excel
            </a>
            <a href="{{ route('hod.history.export.pdf', ['month' => request('month')]) }}" target="_blank" 
               class="flex items-center gap-2 px-6 py-3 bg-[#E57373] text-white text-sm font-bold rounded-2xl shadow-lg shadow-red-200 hover:bg-[#EF5350] transition-all duration-300 transform hover:-translate-y-1">
                <i class="fa-solid fa-file-pdf text-lg"></i> Export PDF
            </a>
        </div>
    </div>

    <div class="bg-white rounded-3xl p-6 border border-gray-100 custom-shadow">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <form action="{{ route('hod.history') }}" method="GET" class="space-y-2">
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Periode</label>
                <input type="month" name="month" value="{{ request('month') }}" onchange="this.form.submit()"
                       class="w-full px-4 py-3 rounded-2xl border border-gray-200 bg-gray-50 text-sm font-semibold text-gray-700 focus:bg-white focus:ring-2 focus:ring-[#8BA888] focus:border-[#8BA888] transition-all outline-none cursor-pointer">
            </form>

            <div class="space-y-2">
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Jenis Izin</label>
                <select id="dt-filter-type" class="w-full px-4 py-3 rounded-2xl border border-gray-200 bg-white text-sm font-semibold text-gray-700 focus:ring-2 focus:ring-[#8BA888] focus:border-[#8BA888] outline-none cursor-pointer transition-all">
                    <option value="">Semua Jenis</option>
                    <option value="tugas">Tugas Keluar</option>
                    <option value="pribadi">Izin Pribadi</option>
                </select>
            </div>

            <div class="space-y-2">
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Status</label>
                <select id="dt-filter-status" class="w-full px-4 py-3 rounded-2xl border border-gray-200 bg-white text-sm font-semibold text-gray-700 focus:ring-2 focus:ring-[#8BA888] focus:border-[#8BA888] outline-none cursor-pointer transition-all">
                    <option value="">Semua Status</option>
                    <option value="selesai">Selesai</option>
                    <option value="sedang di luar">Sedang di Luar</option>
                    <option value="belum keluar">Belum Keluar</option>
                </select>
            </div>

            <div class="space-y-2">
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Pencarian</label>
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" id="dt-search" placeholder="Nama atau NIK..."
                           class="w-full pl-11 pr-4 py-3 rounded-2xl border border-gray-200 bg-gray-50 text-sm font-semibold focus:bg-white focus:ring-2 focus:ring-[#8BA888] transition-all outline-none">
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-[2rem] shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-[#8BA888] to-[#006C68] h-1.5"></div>
        
        <div class="flex justify-between items-center px-8 py-5 border-b border-gray-50">
            <h3 class="font-bold text-gray-700 flex items-center gap-2">
                <i class="fa-solid fa-list-ul text-sage-primary"></i>
                Data Transaksi
            </h3>
            <div class="flex items-center gap-3">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Baris:</span>
                <select id="dt-length" class="px-3 py-1.5 rounded-xl border border-gray-200 bg-gray-50 text-xs font-bold text-gray-700 focus:ring-2 focus:ring-[#8BA888] outline-none cursor-pointer">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto p-2">
            <table id="historyTable" class="w-full">
                <thead>
                    <tr class="text-gray-400 text-[11px] font-bold">
                        <th class="px-6 py-4 text-left rounded-tl-2xl">KARYAWAN</th>
                        <th class="px-6 py-4 text-left">DETAIL IZIN</th>
                        <th class="px-6 py-4 text-left">ESTIMASI</th>
                        <th class="px-6 py-4 text-left">AKTUAL</th>
                        <th class="px-6 py-4 text-center rounded-tr-2xl">STATUS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($permits as $permit)
                    <tr class="group hover:bg-gray-50/80 transition-all duration-200">
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-[#F1F4F1] to-[#DDE5DD] text-[#5F7461] flex items-center justify-center font-bold text-sm shadow-inner border border-white">
                                    {{ substr($permit->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800 text-sm group-hover:text-mna-teal transition-colors">{{ $permit->user->name }}</p>
                                    <p class="text-[10px] text-gray-400 font-mono tracking-tighter">{{ $permit->user->nik ?? 'NO-NIK' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex flex-col gap-1.5">
                                <div class="flex gap-2 items-center">
                                    <span class="px-2 py-0.5 rounded-lg bg-gray-100 text-[9px] font-mono font-bold text-gray-600">
                                        #{{ $permit->unique_code ?? 'CODE' }}
                                    </span>
                                    <span class="text-[9px] px-2 py-0.5 rounded-lg font-bold uppercase tracking-wider border {{ $permit->permit_type == 'tugas' ? 'bg-blue-50 text-blue-600 border-blue-100' : 'bg-orange-50 text-orange-600 border-orange-100' }}">
                                        {{ $permit->permit_type }}
                                    </span>
                                </div>
                                <span class="text-xs font-semibold text-gray-600">{{ $permit->permit_date ? \Carbon\Carbon::parse($permit->permit_date)->format('d M Y') : '-' }}</span>
                                <p class="text-[11px] text-gray-400 italic truncate max-w-[150px]">"{{ $permit->reason }}"</p>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2 text-xs">
                                    <i class="fa-solid fa-sign-out text-red-300 w-4"></i>
                                    <span class="font-mono font-semibold text-gray-600">{{ $permit->target_time_out ? \Carbon\Carbon::parse($permit->target_time_out)->format('H:i') : '--:--' }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-xs">
                                    <i class="fa-solid fa-sign-in text-green-300 w-4"></i>
                                    <span class="font-mono font-semibold text-gray-600">{{ $permit->target_time_in ? \Carbon\Carbon::parse($permit->target_time_in)->format('H:i') : '--:--' }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="space-y-1.5">
                                <div class="text-[11px] font-bold text-gray-700 flex flex-col">
                                    <span>Keluar: {{ $permit->time_out ? \Carbon\Carbon::parse($permit->time_out)->format('H:i') : '---' }}</span>
                                    <span>Masuk: {{ $permit->time_in ? \Carbon\Carbon::parse($permit->time_in)->format('H:i') : '---' }}</span>
                                </div>
                                @if($permit->late_minutes > 0)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-red-50 text-red-500 font-bold text-[9px] rounded-md">
                                        <i class="fa-solid fa-circle-exclamation animate-pulse"></i> Telat {{ $permit->late_minutes }}m
                                    </span>
                                @elseif($permit->status == 'returned' && $permit->late_minutes == 0)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-green-50 text-green-600 font-bold text-[9px] rounded-md">
                                        <i class="fa-solid fa-check-double"></i> On Time
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            @if($permit->status == 'returned')
                                <span class="px-4 py-1.5 bg-green-50 text-green-700 text-[10px] font-bold rounded-xl border border-green-100 block text-center shadow-sm">SELESAI</span>
                            @elseif($permit->status == 'out')
                                <span class="px-4 py-1.5 bg-blue-50 text-blue-700 text-[10px] font-bold rounded-xl border border-blue-100 block text-center animate-pulse">DI LUAR</span>
                            @else
                                <span class="px-4 py-1.5 bg-gray-50 text-gray-600 text-[10px] font-bold rounded-xl border border-gray-100 block text-center">PENDING</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="px-8 py-6 bg-gray-50/50">
            <div id="pagination-container"></div>
        </div>
    </div>
</div>



<script>
    $(document).ready(function() {
        var table = $('#historyTable').DataTable({
            pageLength: 10,
            ordering: false,
            dom: 'tip', // Hanya tampilkan Table, Info, dan Paginate
            language: {
                info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ data",
                paginate: {
                    next: '<i class="fa-solid fa-chevron-right text-[10px]"></i>',
                    previous: '<i class="fa-solid fa-chevron-left text-[10px]"></i>'
                }
            }
        });

        // Search
        $('#dt-search').on('keyup', function() {
            table.search(this.value).draw();
        });

        // Length
        $('#dt-length').on('change', function() {
            table.page.len($(this).val()).draw();
        });

        // Filter Type
        $('#dt-filter-type').on('change', function() {
            table.column(1).search(this.value).draw();
        });

        // Filter Status
        $('#dt-filter-status').on('change', function() {
            table.column(4).search(this.value).draw();
        });
    });
</script>
@endsection