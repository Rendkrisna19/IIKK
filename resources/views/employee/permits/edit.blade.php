@extends('layouts.app')
@section('title', 'Edit Pengajuan Izin')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

@section('content')
<div class="max-w-4xl mx-auto" x-data="{ jenisIzin: '{{ old('permit_type', $permit->permit_type) }}' }">
    
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('employee.my-permits') }}" class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-gray-500 hover:text-mna-dark shadow-sm transition">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h2 class="text-2xl font-bold text-gray-800">Edit Pengajuan Izin</h2>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden p-8 hover:shadow-lg transition-shadow duration-300">
        
        <form action="{{ route('employee.permit.update', $permit->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            <i class="fa-solid fa-calendar-day text-mna-teal mr-2"></i> Tanggal Izin <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="permit_date" 
                               value="{{ old('permit_date', $permit->permit_date ? \Carbon\Carbon::parse($permit->permit_date)->format('Y-m-d') : '') }}" 
                               class="w-full rounded-xl border-gray-200 bg-gray-50 p-3.5 text-sm focus:bg-white focus:ring-2 focus:ring-mna-teal transition" required>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            <i class="fa-solid fa-list-check text-mna-teal mr-2"></i> Jenis Izin <span class="text-red-500">*</span>
                        </label>
                        <select name="permit_type" x-model="jenisIzin" class="w-full rounded-xl border-gray-200 bg-gray-50 p-3.5 text-sm focus:bg-white focus:ring-2 focus:ring-mna-teal transition cursor-pointer" required>
                            <option value="tugas">Tugas Keluar Kantor</option>
                            <option value="pribadi">Keperluan Pribadi</option>
                        </select>
                    </div>

                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            <i class="fa-solid fa-clock text-mna-teal mr-2"></i> Rencana Jam Keluar <span class="text-red-500">*</span>
                        </label>
                        <input type="time" name="target_time_out" 
                               value="{{ old('target_time_out', $permit->target_time_out ? \Carbon\Carbon::parse($permit->target_time_out)->format('H:i') : '') }}" 
                               class="w-full rounded-xl border-gray-200 bg-gray-50 p-3.5 text-sm focus:bg-white focus:ring-2 focus:ring-mna-teal transition" required>
                    </div>

                    <div x-show="jenisIzin === 'pribadi'" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform -translate-y-2"
                         x-transition:enter-end="opacity-100 transform translate-y-0">
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            <i class="fa-solid fa-clock-rotate-left text-mna-teal mr-2"></i> Rencana Jam Kembali <span class="text-red-500">*</span>
                        </label>
                        <input type="time" name="target_time_in" 
                               value="{{ old('target_time_in', $permit->target_time_in ? \Carbon\Carbon::parse($permit->target_time_in)->format('H:i') : '') }}" 
                               class="w-full rounded-xl border-gray-200 bg-gray-50 p-3.5 text-sm focus:bg-white focus:ring-2 focus:ring-mna-teal transition" 
                               :required="jenisIzin === 'pribadi'">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        <i class="fa-solid fa-align-left text-mna-teal mr-2"></i> Keperluan / Alasan <span class="text-red-500">*</span>
                    </label>
                    <textarea name="reason" rows="4" class="w-full rounded-xl border-gray-200 bg-gray-50 p-3.5 text-sm focus:bg-white focus:ring-2 focus:ring-mna-teal transition" required placeholder="Jelaskan keperluan izin Anda secara rinci...">{{ old('reason', $permit->reason) }}</textarea>
                </div>
                
                <hr class="border-gray-100 my-6">

                <div class="flex justify-end gap-3">
                    <a href="{{ route('employee.my-permits') }}" class="px-6 py-3 rounded-xl text-sm font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 transition">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-3 rounded-xl text-sm font-bold text-white bg-mna-dark hover:bg-mna-teal transition flex items-center gap-2 shadow-lg shadow-mna-teal/20">
                        <i class="fa-solid fa-save"></i> Simpan Perubahan
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>
@endsection