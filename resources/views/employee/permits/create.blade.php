@extends('layouts.app')
@section('title', 'Buat Pengajuan Izin')

@section('content')
<div class="max-w-6xl mx-auto">

    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-800">Form Izin Keluar Kantor (IKK)</h2>
        <p class="text-gray-500 mt-1">Isi data dengan benar. Surat izin / QR Code hanya akan terbit setelah disetujui atasan.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden relative">
                
                <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-mna-dark to-mna-teal"></div>

                <form action="{{ route('employee.permit.store') }}" method="POST" class="p-8">
                    @csrf

                    <div class="mb-8 p-5 bg-gray-50 rounded-xl border border-gray-100">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 border-b border-gray-200 pb-2">Data Pemohon</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Nama Lengkap</label>
                                <div class="font-bold text-gray-800">{{ Auth::user()->name }}</div>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">NIK</label>
                                <div class="font-bold text-gray-800">{{ Auth::user()->nik ?? '-' }}</div>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Departemen</label>
                                <div class="font-bold text-gray-800">{{ Auth::user()->department->name ?? '-' }}</div>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Jabatan</label>
                                <div class="font-bold text-gray-800">{{ Auth::user()->position ?? '-' }}</div>
                            </div>
                        </div>
                    </div>

                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Detail Izin</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <label class="cursor-pointer group">
                            <input type="radio" name="permit_type" value="tugas" class="peer sr-only" required>
                            <div class="p-4 rounded-xl border-2 border-gray-100 bg-white peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all hover:border-blue-200 h-full">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <span class="font-bold text-gray-700 peer-checked:text-blue-700">Tugas Kantor</span>
                                </div>
                                <p class="text-xs text-gray-500 pl-11">Dinas luar, meeting vendor, beli sparepart, dll.</p>
                            </div>
                        </label>

                        <label class="cursor-pointer group">
                            <input type="radio" name="permit_type" value="pribadi" class="peer sr-only" required>
                            <div class="p-4 rounded-xl border-2 border-gray-100 bg-white peer-checked:border-orange-500 peer-checked:bg-orange-50 transition-all hover:border-orange-200 h-full">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    </div>
                                    <span class="font-bold text-gray-700 peer-checked:text-orange-700">Izin Pribadi</span>
                                </div>
                                <p class="text-xs text-gray-500 pl-11">Sakit, urusan keluarga, pulang cepat, dll.</p>
                            </div>
                        </label>
                    </div>

                    <div class="mb-8">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Alasan / Deskripsi Keperluan</label>
                        <textarea name="reason" rows="4" required placeholder="Jelaskan alasan Anda keluar kantor secara detail..."
                            class="w-full p-4 rounded-xl border border-gray-300 focus:outline-none focus:border-mna-teal focus:ring-4 focus:ring-mna-teal/10 transition-all"></textarea>
                    </div>

                    <div class="flex items-center justify-between pt-6 border-t border-gray-100">
                        <a href="{{ route('dashboard') }}" class="text-sm font-medium text-gray-500 hover:text-gray-800">Batal</a>
                        <button type="submit" class="px-8 py-3 bg-mna-dark text-white font-bold rounded-xl hover:bg-mna-teal shadow-lg shadow-mna-teal/30 hover:-translate-y-1 transition-all flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Kirim Permohonan
                        </button>
                    </div>

                </form>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-6">
                <h3 class="font-bold text-gray-800 mb-6">Alur Penerbitan Izin</h3>
                
                <div class="relative pl-4 border-l-2 border-gray-100 space-y-8">
                    
                    <div class="relative">
                        <span class="absolute -left-[21px] flex h-8 w-8 items-center justify-center rounded-full bg-mna-teal text-white ring-4 ring-white">
                            1
                        </span>
                        <div class="ml-4">
                            <h4 class="text-sm font-bold text-gray-800">Isi Form & Kirim</h4>
                            <p class="text-xs text-gray-500 mt-1">Anda mengisi data keperluan dan klik kirim. Status menjadi <span class="text-yellow-600 font-bold">Pending</span>.</p>
                        </div>
                    </div>

                    <div class="relative">
                        <span class="absolute -left-[21px] flex h-8 w-8 items-center justify-center rounded-full bg-gray-200 text-gray-500 ring-4 ring-white">
                            2
                        </span>
                        <div class="ml-4">
                            <h4 class="text-sm font-bold text-gray-500">Approval HOD/Manager</h4>
                            <p class="text-xs text-gray-400 mt-1">Atasan Anda menerima notifikasi dan melakukan persetujuan (Approve/Reject).</p>
                        </div>
                    </div>

                    <div class="relative">
                        <span class="absolute -left-[21px] flex h-8 w-8 items-center justify-center rounded-full bg-gray-200 text-gray-500 ring-4 ring-white">
                            3
                        </span>
                        <div class="ml-4">
                            <h4 class="text-sm font-bold text-gray-500">QR Code & Cetak</h4>
                            <p class="text-xs text-gray-400 mt-1">Jika disetujui, surat izin digital terbit. Anda bisa <b>Cetak Surat</b> atau <b>Scan Barcode</b> di pos security.</p>
                        </div>
                    </div>

                    <div class="relative">
                        <span class="absolute -left-[21px] flex h-8 w-8 items-center justify-center rounded-full bg-gray-200 text-gray-500 ring-4 ring-white">
                            4
                        </span>
                        <div class="ml-4">
                            <h4 class="text-sm font-bold text-gray-500">Scan Security</h4>
                            <p class="text-xs text-gray-400 mt-1">Security memindai barcode saat Anda Keluar dan Masuk gerbang.</p>
                        </div>
                    </div>

                </div>

                <div class="mt-8 bg-blue-50 p-4 rounded-xl flex gap-3 items-start">
                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-xs text-blue-800 leading-relaxed">
                        Pastikan data yang diisi sesuai. Penyalahgunaan izin akan dikenakan sanksi sesuai peraturan perusahaan.
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection