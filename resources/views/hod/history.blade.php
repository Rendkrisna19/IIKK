@extends('layouts.app')

@section('title', 'Histori & Laporan')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

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
        padding-top: 1rem !important;
        display: flex !important;
        justify-content: flex-end !important;
        gap: 4px;
        align-items: center;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border: 1px solid #e2e8f0 !important;
        background: white !important;
        color: #64748b !important;
        border-radius: 8px !important;
        padding: 0.4rem 0.8rem !important;
        font-weight: 600 !important;
        font-size: 0.85rem !important;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: var(--sage-light) !important;
        color: var(--sage-dark) !important;
        border-color: var(--sage-primary) !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current,
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        background: var(--mna-teal) !important;
        color: white !important;
        border-color: var(--mna-teal) !important;
        box-shadow: 0 4px 6px -1px rgba(0, 108, 104, 0.2) !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .dataTables_wrapper .dataTables_info {
        text-align: left;
        padding-top: 1.2rem !important;
        font-size: 0.85rem;
        color: #64748b;
        font-weight: 500;
        float: left;
    }

    /* Hide Default Elements */
    .dataTables_length, .dataTables_filter { display: none !important; }
    table.dataTable.no-footer { border-bottom: none !important; }

    /* Table Aesthetic */
    #historyTable { border-collapse: collapse !important; }
    #historyTable thead th {
        background-color: #F8FAFC;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 2px solid #F1F5F9 !important;
        color: #94a3b8;
        font-size: 0.7rem;
        padding-top: 1rem;
        padding-bottom: 1rem;
    }
    
    /* Hilangkan panah sorting bawaan datatable yg jelek */
    table.dataTable thead .sorting, 
    table.dataTable thead .sorting_asc, 
    table.dataTable thead .sorting_desc {
        background-image: none !important;
    }

    .custom-shadow {
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
    }

    .focus-sage:focus {
        border-color: var(--sage-primary);
        box-shadow: 0 0 0 3px rgba(139, 168, 136, 0.2);
    }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto space-y-8 py-6 px-4" x-data="{ cancelModalOpen: false, cancelId: '' }">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-800 tracking-tight">Histori & <span class="text-mna-teal">Laporan</span></h2>
            <p class="text-gray-500 mt-2 font-medium">Manajemen data absensi dan izin karyawan secara terpusat.</p>
        </div>
        
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('hod.history.export.excel', ['month' => $filterMonth]) }}" 
               class="flex items-center gap-2 px-6 py-3 bg-white border-2 border-[#8BA888] text-[#5F7461] hover:bg-[#F1F4F1] text-sm font-bold rounded-2xl transition-all duration-300 transform hover:-translate-y-1">
                <i class="fa-solid fa-file-excel text-lg"></i> Export Excel
            </a>
            <a href="{{ route('hod.history.export.pdf', ['month' => $filterMonth]) }}" target="_blank" 
               class="flex items-center gap-2 px-6 py-3 bg-[#E57373] text-white text-sm font-bold rounded-2xl shadow-lg shadow-red-200 hover:bg-[#EF5350] transition-all duration-300 transform hover:-translate-y-1">
                <i class="fa-solid fa-file-pdf text-lg"></i> Export PDF
            </a>
        </div>
    </div>

    <div class="bg-white rounded-3xl p-6 border border-gray-100 custom-shadow">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            
            <form action="{{ route('hod.history') }}" method="GET" class="space-y-2">
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Periode Bulan</label>
                <input type="month" name="month" value="{{ $filterMonth }}" onchange="this.form.submit()"
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
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Pencarian Cepat</label>
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" id="dt-search" placeholder="Ketik Nama / NIK..."
                           class="w-full pl-11 pr-4 py-3 rounded-2xl border border-gray-200 bg-gray-50 text-sm font-semibold focus:bg-white focus:ring-2 focus:ring-[#8BA888] transition-all outline-none">
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-[2rem] shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-[#8BA888] to-[#006C68] h-1.5"></div>
        
        <div class="flex justify-between items-center px-8 py-5 border-b border-gray-50">
            <h3 class="font-bold text-gray-700 flex items-center gap-2 text-sm uppercase tracking-wide">
                <i class="fa-solid fa-table-list text-sage-primary"></i>
                Data Transaksi Izin
            </h3>
            <div class="flex items-center gap-3">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Tampilkan:</span>
                <select id="dt-length" class="px-3 py-1.5 rounded-xl border border-gray-200 bg-gray-50 text-xs font-bold text-gray-700 focus:ring-2 focus:ring-[#8BA888] outline-none cursor-pointer shadow-sm">
                    <option value="10">10 Baris</option>
                    <option value="25">25 Baris</option>
                    <option value="50">50 Baris</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table id="historyTable" class="w-full text-left whitespace-nowrap">
                <thead>
                    <tr>
                        <th class="hidden">DateSort</th> 
                        <th class="px-6 py-4 rounded-tl-2xl">Karyawan</th>
                        <th class="px-6 py-4">Detail Izin</th>
                        <th class="px-6 py-4">Estimasi Waktu</th>
                        <th class="px-6 py-4">Aktual Scan</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center rounded-tr-2xl">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($permits as $permit)
                    <tr class="group hover:bg-gray-50/80 transition-all duration-200">
                        
                        <td class="hidden">{{ Carbon\Carbon::parse($permit->permit_date)->format('Y-m-d H:i:s') }}</td>

                        <td class="px-6 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 shrink-0 rounded-2xl bg-gradient-to-br from-[#F1F4F1] to-[#DDE5DD] text-[#5F7461] flex items-center justify-center font-bold text-sm shadow-inner border border-white">
                                    {{ substr($permit->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800 text-[13px] group-hover:text-mna-teal transition-colors truncate max-w-[150px]">{{ $permit->user->name }}</p>
                                    <p class="text-[10px] text-gray-400 font-mono tracking-tighter mt-0.5">{{ $permit->user->nik ?? 'NO-NIK' }}</p>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-5">
                            <div class="flex flex-col gap-1.5">
                                <div class="flex gap-2 items-center">
                                    <span class="px-2 py-0.5 rounded-md bg-gray-100 text-[9px] font-mono font-bold text-gray-600 border border-gray-200">
                                        #{{ $permit->unique_code ?? 'CODE' }}
                                    </span>
                                    <span class="hidden">{{ $permit->permit_type }}</span>
                                    <span class="text-[9px] px-2 py-0.5 rounded-md font-bold uppercase tracking-wider border {{ $permit->permit_type == 'tugas' ? 'bg-blue-50 text-blue-600 border-blue-100' : 'bg-orange-50 text-orange-600 border-orange-100' }}">
                                        {{ $permit->permit_type }}
                                    </span>
                                </div>
                                <span class="text-xs font-semibold text-gray-700">{{ $permit->permit_date ? \Carbon\Carbon::parse($permit->permit_date)->format('d M Y') : '-' }}</span>
                                <p class="text-[10px] text-gray-500 italic truncate max-w-[180px]" title="{{ $permit->reason }}">"{{ $permit->reason }}"</p>
                            </div>
                        </td>

                        <td class="px-6 py-5">
                            <div class="space-y-1.5">
                                <div class="flex items-center gap-2 text-xs">
                                    <div class="w-5 flex justify-center"><i class="fa-solid fa-arrow-right-from-bracket text-red-400"></i></div>
                                    <span class="font-mono font-semibold text-gray-600 bg-gray-50 px-2 py-0.5 rounded border border-gray-100">{{ $permit->target_time_out ? \Carbon\Carbon::parse($permit->target_time_out)->format('H:i') : '--:--' }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-xs">
                                    <div class="w-5 flex justify-center"><i class="fa-solid fa-arrow-right-to-bracket text-green-500"></i></div>
                                    <span class="font-mono font-semibold text-gray-600 bg-gray-50 px-2 py-0.5 rounded border border-gray-100">{{ $permit->target_time_in ? \Carbon\Carbon::parse($permit->target_time_in)->format('H:i') : '--:--' }}</span>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-5">
                            <div class="space-y-1.5">
                                <div class="text-[11px] font-bold text-gray-700 flex flex-col gap-1">
                                    <div class="flex items-center gap-2">
                                        <i class="fa-solid fa-clock text-gray-400 w-3"></i>
                                        <span>Out: {{ $permit->time_out ? \Carbon\Carbon::parse($permit->time_out)->format('H:i') : '---' }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <i class="fa-solid fa-clock text-gray-400 w-3"></i>
                                        <span>In: &nbsp;{{ $permit->time_in ? \Carbon\Carbon::parse($permit->time_in)->format('H:i') : '---' }}</span>
                                    </div>
                                </div>
                                
                                @if($permit->late_minutes > 0)
                                    <span class="inline-flex items-center gap-1.5 px-2 py-1 bg-red-50 border border-red-100 text-red-600 font-bold text-[9px] rounded-lg mt-1 shadow-sm">
                                        <span class="relative flex h-1.5 w-1.5">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-red-500"></span>
                                        </span>
                                        Telat {{ $permit->late_minutes }} m
                                    </span>
                                @elseif($permit->status == 'returned' && $permit->late_minutes == 0 && $permit->permit_type == 'pribadi')
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-50 border border-green-100 text-green-600 font-bold text-[9px] rounded-lg mt-1 shadow-sm">
                                        <i class="fa-solid fa-check-double text-green-500"></i> On Time
                                    </span>
                                @endif
                            </div>
                        </td>

                        <td class="px-6 py-5 text-center">
                            @if($permit->status == 'returned')
                                <span class="px-4 py-1.5 bg-green-50 text-green-700 text-[10px] font-bold rounded-xl border border-green-200 block shadow-sm">SELESAI</span>
                            @elseif($permit->status == 'out')
                                <span class="px-4 py-1.5 bg-blue-50 text-blue-700 text-[10px] font-bold rounded-xl border border-blue-200 block animate-pulse">DI LUAR</span>
                            @elseif($permit->status == 'cancelled')
                                <span class="px-4 py-1.5 bg-red-50 text-red-700 text-[10px] font-bold rounded-xl border border-red-200 block shadow-sm" title="{{ $permit->cancel_message }}">DIBATALKAN</span>
                            @elseif($permit->status == 'expired')
                                <span class="px-4 py-1.5 bg-gray-200 text-gray-700 text-[10px] font-bold rounded-xl border border-gray-300 block shadow-sm" title="{{ $permit->cancel_message }}">KADALUARSA</span>
                            @else
                                <span class="px-4 py-1.5 bg-gray-50 text-gray-600 text-[10px] font-bold rounded-xl border border-gray-200 block">BELUM KELUAR</span>
                            @endif
                        </td>

                        <td class="px-6 py-5 text-center flex justify-center gap-2">
                            @if($permit->status == 'approved')
                                @if($permit->user_id == Auth::id())
                                    <a href="{{ route('hod.permit.print', $permit->id) }}" target="_blank"
                                            class="px-3 py-1.5 bg-teal-50 text-teal-600 hover:bg-teal-600 hover:text-white rounded text-[10px] font-bold transition-all border border-teal-200 inline-flex items-center gap-1">
                                        <i class="fa-solid fa-print"></i> Cetak QR
                                    </a>
                                @endif
                                <button @click="cancelModalOpen = true; cancelId = '{{ $permit->id }}'" 
                                        class="px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded text-[10px] font-bold transition-all border border-red-200 inline-flex items-center gap-1">
                                    <i class="fa-solid fa-ban"></i> Batal
                                </button>
                            @else
                                <span class="text-[10px] text-gray-400 italic">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-8 py-5 border-t border-gray-100 bg-gray-50/30 overflow-hidden clearfix">
            </div>
    </div>

    <!-- Cancel Modal -->
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
            <p class="text-sm text-gray-500 mb-6">Silakan isi alasan mengapa izin ini dibatalkan oleh HOD.</p>
            
            <form :action="'{{ url('hod/permit') }}/' + cancelId + '/cancel'" method="POST">
                @csrf
                @method('PATCH')
                
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Alasan Pembatalan <span class="text-red-500">*</span></label>
                    <textarea name="cancel_message" rows="3" required class="w-full rounded-xl border-gray-200 bg-gray-50 p-3 text-sm focus:bg-white focus:ring-2 focus:ring-red-500 outline-none transition" placeholder="Contoh: Karena ada urgensi internal, karyawan tidak jadi keluar..."></textarea>
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

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        var table = $('#historyTable').DataTable({
            pageLength: 10,
            // Urutkan berdasarkan kolom ke-0 (DateSort hidden) secara Descending
            order: [[0, 'desc']], 
            // Struktur DOM DataTables
            // <"wrapper" t <"bottom flex justify-between items-center" i p> >
            dom: '<"table-responsive"t><"mt-4 flex flex-col sm:flex-row justify-between items-center gap-4"ip>',
            language: {
                info: "Menampilkan _START_ s/d _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data ditemukan",
                zeroRecords: "Tidak ada histori yang cocok dengan filter.",
                paginate: {
                    next: 'Selanjutnya <i class="fa-solid fa-angle-right ml-1"></i>',
                    previous: '<i class="fa-solid fa-angle-left mr-1"></i> Prev'
                }
            }
        });

        // Search Karyawan/NIK
        $('#dt-search').on('keyup', function() {
            table.search(this.value).draw();
        });

        // Jumlah Baris
        $('#dt-length').on('change', function() {
            table.page.len($(this).val()).draw();
        });

        // Filter Jenis Izin (Kolom ke-2 Index 2 karena ada hidden column DateSort di Index 0)
        $('#dt-filter-type').on('change', function() {
            table.column(2).search(this.value).draw();
        });

        // Filter Status (Kolom ke-5 Index 5)
        $('#dt-filter-status').on('change', function() {
            table.column(5).search(this.value).draw();
        });
    });
</script>
@endsection