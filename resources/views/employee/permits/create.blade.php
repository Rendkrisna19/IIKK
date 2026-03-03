@extends('layouts.app')
@section('title', 'Buat Pengajuan Izin')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

@section('content')
<div class="max-w-6xl mx-auto pb-10">

    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-800">Form Izin Keluar Kantor (IKK)</h2>
        <p class="text-gray-500 mt-1">Isi data dengan benar. Surat izin / QR Code hanya akan terbit setelah disetujui atasan.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden relative hover:shadow-md transition-shadow">
                
                <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-mna-dark to-mna-teal"></div>

                <form action="{{ route('employee.permit.store') }}" method="POST" class="p-8" x-data="{ permit_type: 'tugas' }">
                    @csrf

                    <div class="mb-8 p-6 bg-gray-50/50 rounded-xl border border-gray-100 shadow-inner">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 border-b border-gray-200 pb-2 flex items-center">
                            <i class="fa-solid fa-user-circle mr-2"></i> Data Pemohon
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-6">
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Nama Lengkap</label>
                                <div class="font-bold text-gray-800 text-sm">{{ Auth::user()->name }}</div>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">NIK</label>
                                <div class="font-bold text-gray-800 text-sm font-mono">{{ Auth::user()->nik ?? '-' }}</div>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Departemen</label>
                                <div class="font-bold text-gray-800 text-sm">{{ Auth::user()->department->name ?? '-' }}</div>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Jabatan</label>
                                <div class="font-bold text-gray-800 text-sm">{{ Auth::user()->position ?? '-' }}</div>
                            </div>
                        </div>
                    </div>

                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 border-b border-gray-100 pb-2">Detail Izin</h3>
                    
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            <i class="fa-solid fa-calendar-day text-mna-teal mr-2"></i>Tanggal Izin <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="permit_date" required min="{{ date('Y-m-d') }}"
                               class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:outline-none focus:border-mna-teal focus:ring-4 focus:ring-mna-teal/10 transition-all text-sm">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <label class="cursor-pointer group">
                            <input type="radio" name="permit_type" value="tugas" x-model="permit_type" class="peer sr-only" required>
                            <div class="p-4 rounded-xl border-2 border-gray-100 bg-white peer-checked:border-mna-teal peer-checked:bg-mna-light transition-all hover:border-mna-teal/40 h-full relative overflow-hidden">
                                <div class="absolute top-0 right-0 w-16 h-16 bg-mna-teal/5 rounded-bl-full -mr-8 -mt-8 peer-checked:bg-mna-teal/10 transition-colors"></div>
                                <div class="flex items-center gap-3 mb-2 relative z-10">
                                    <div class="w-10 h-10 rounded-full bg-gray-100 text-gray-500 flex items-center justify-center peer-checked:bg-mna-teal peer-checked:text-white transition-colors shadow-sm">
                                        <i class="fa-solid fa-briefcase text-lg"></i>
                                    </div>
                                    <span class="font-bold text-gray-700 peer-checked:text-mna-dark text-lg">Tugas Kantor</span>
                                </div>
                                <p class="text-xs text-gray-500 pl-[3.25rem] relative z-10">Dinas luar, meeting vendor, site visit, dll.</p>
                            </div>
                        </label>

                        <label class="cursor-pointer group">
                            <input type="radio" name="permit_type" value="pribadi" x-model="permit_type" class="peer sr-only" required>
                            <div class="p-4 rounded-xl border-2 border-gray-100 bg-white peer-checked:border-orange-500 peer-checked:bg-orange-50 transition-all hover:border-orange-200 h-full relative overflow-hidden">
                                <div class="absolute top-0 right-0 w-16 h-16 bg-orange-500/5 rounded-bl-full -mr-8 -mt-8 peer-checked:bg-orange-500/10 transition-colors"></div>
                                <div class="flex items-center gap-3 mb-2 relative z-10">
                                    <div class="w-10 h-10 rounded-full bg-gray-100 text-gray-500 flex items-center justify-center peer-checked:bg-orange-500 peer-checked:text-white transition-colors shadow-sm">
                                        <i class="fa-solid fa-person-walking-arrow-right text-lg"></i>
                                    </div>
                                    <span class="font-bold text-gray-700 peer-checked:text-orange-700 text-lg">Izin Pribadi</span>
                                </div>
                                <p class="text-xs text-gray-500 pl-[3.25rem] relative z-10">Sakit, urusan keluarga, pulang lebih awal, dll.</p>
                            </div>
                        </label>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">
                                <i class="fa-solid fa-clock text-mna-teal mr-2"></i>Rencana Jam Keluar
                            </label>
                            <input type="time" name="target_time_out" required 
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:outline-none focus:border-mna-teal focus:ring-4 focus:ring-mna-teal/10 transition-all font-mono text-sm">
                        </div>

                        <div x-show="permit_type === 'pribadi'" 
                             x-transition:enter="transition ease-out duration-300" 
                             x-transition:enter-start="opacity-0 transform -translate-y-2" 
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             style="display: none;">
                            <label class="block text-sm font-bold text-gray-700 mb-2">
                                <i class="fa-solid fa-clock-rotate-left text-orange-500 mr-2"></i>Target Jam Kembali
                            </label>
                            <input type="time" name="target_time_in" :required="permit_type === 'pribadi'" 
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 transition-all font-mono text-sm">
                            <p class="text-[10px] text-red-500 mt-1.5 font-medium">*Wajib diisi. Keterlambatan akan dicatat otomatis.</p>
                        </div>
                    </div>

                    <div class="mb-8">
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            <i class="fa-solid fa-align-left text-mna-teal mr-2"></i>Alasan / Deskripsi Keperluan
                        </label>
                        <textarea name="reason" rows="4" required placeholder="Jelaskan alasan Anda keluar area kantor secara detail..."
                            class="w-full p-4 rounded-xl border border-gray-300 focus:outline-none focus:border-mna-teal focus:ring-4 focus:ring-mna-teal/10 transition-all text-sm"></textarea>
                    </div>

                    <div class="flex items-center justify-between pt-6 border-t border-gray-100">
                        <a href="{{ route('employee.my-permits') }}" class="text-sm font-bold text-gray-500 hover:text-gray-800 transition-colors bg-gray-100 px-5 py-2.5 rounded-xl">Batal</a>
                        <button type="submit" class="px-8 py-3 bg-gradient-to-r from-mna-dark to-mna-teal text-white font-bold rounded-xl shadow-lg shadow-mna-teal/30 hover:shadow-xl hover:-translate-y-0.5 transition-all flex items-center">
                            <i class="fa-solid fa-paper-plane mr-2"></i> Kirim Permohonan
                        </button>
                    </div>

                </form>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-6">
                <h3 class="font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fa-solid fa-route text-mna-teal mr-2 text-xl"></i> Alur Penerbitan Izin
                </h3>
                
                <div class="relative pl-4 border-l-2 border-gray-100 space-y-8">
                    
                    <div class="relative">
                        <span class="absolute -left-[21px] flex h-8 w-8 items-center justify-center rounded-full bg-mna-teal text-white ring-4 ring-white font-bold text-sm">1</span>
                        <div class="ml-4">
                            <h4 class="text-sm font-bold text-gray-800">Isi Form & Kirim</h4>
                            <p class="text-xs text-gray-500 mt-1 leading-relaxed">Isi tanggal, keperluan, dan jam target. Status awal <span class="text-yellow-600 font-bold">Pending</span>.</p>
                        </div>
                    </div>

                    <div class="relative">
                        <span class="absolute -left-[21px] flex h-8 w-8 items-center justify-center rounded-full bg-gray-100 text-gray-400 ring-4 ring-white font-bold text-sm border border-gray-200">2</span>
                        <div class="ml-4">
                            <h4 class="text-sm font-bold text-gray-500">Approval HOD</h4>
                            <p class="text-xs text-gray-400 mt-1 leading-relaxed">Manager/HOD akan memeriksa pengajuan Anda (Approve/Reject).</p>
                        </div>
                    </div>

                    <div class="relative">
                        <span class="absolute -left-[21px] flex h-8 w-8 items-center justify-center rounded-full bg-gray-100 text-gray-400 ring-4 ring-white font-bold text-sm border border-gray-200">3</span>
                        <div class="ml-4">
                            <h4 class="text-sm font-bold text-gray-500">QR Code Diterbitkan</h4>
                            <p class="text-xs text-gray-400 mt-1 leading-relaxed">Jika disetujui, QR Code dan dokumen akan otomatis diterbitkan.</p>
                        </div>
                    </div>

                    <div class="relative">
                        <span class="absolute -left-[21px] flex h-8 w-8 items-center justify-center rounded-full bg-gray-100 text-gray-400 ring-4 ring-white font-bold text-sm border border-gray-200">4</span>
                        <div class="ml-4">
                            <h4 class="text-sm font-bold text-gray-500">Scan di Pos Security</h4>
                            <p class="text-xs text-gray-400 mt-1 leading-relaxed">Tunjukkan tiket / E-Pass di pos keamanan untuk scan keluar masuk.</p>
                        </div>
                    </div>

                </div>

                <div class="mt-8 bg-orange-50 p-4 rounded-xl flex gap-3 items-start border border-orange-100">
                    <i class="fa-solid fa-triangle-exclamation text-orange-500 mt-0.5"></i>
                    <p class="text-[11px] text-orange-800 leading-relaxed font-medium">
                        Keterlambatan saat jam kembali (untuk Izin Pribadi) akan terekam oleh sistem dan dapat mempengaruhi evaluasi.
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection