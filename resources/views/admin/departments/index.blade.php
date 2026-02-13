@extends('layouts.app')
@section('title', 'Manajemen Departemen')

@section('content')
<div x-data="deptHandler()" class="max-w-7xl mx-auto">

    <div class="flex flex-col md:flex-row justify-between items-end md:items-center gap-4 mb-8">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Unit Departemen</h2>
            <p class="text-gray-500 mt-1">Kelola struktur organisasi dan divisi perusahaan.</p>
        </div>
        <button @click="openModal('create')" 
            class="bg-mna-dark hover:bg-mna-teal text-white px-6 py-3 rounded-xl text-sm font-bold transition-all shadow-lg shadow-mna-teal/20 flex items-center group">
            <span class="bg-white/20 p-1 rounded-md mr-3 group-hover:rotate-90 transition-transform">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
            </span>
            Buat Departemen Baru
        </button>
    </div>

    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
             class="fixed top-5 right-5 z-50 bg-green-500 text-white px-6 py-4 rounded-xl shadow-2xl flex items-center animate-bounce-in">
            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif
    
    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6 shadow-sm">
            <p class="font-bold">Gagal!</p>
            <p class="text-sm">{{ session('error') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($departments as $index => $dept)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-mna-teal/20 to-mna-dark/5 rounded-full blur-2xl -mr-10 -mt-10 transition-transform group-hover:scale-150"></div>
                <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-mna-dark to-mna-teal transform scale-x-0 group-hover:scale-x-100 transition-transform duration-500 origin-left"></div>
                
                <div class="absolute top-0 right-10 w-20 h-full bg-white/5 skew-x-12 border-l border-white/50 pointer-events-none"></div>

                <div class="p-6 relative z-10">
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-12 h-12 rounded-xl bg-mna-light text-mna-dark flex items-center justify-center text-xl shadow-inner">
                            @if($dept->id % 4 == 0)
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            @elseif($dept->id % 4 == 1)
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            @elseif($dept->id % 4 == 2)
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            @else
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            @endif
                        </div>
                        
                        <div class="flex space-x-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                            <button @click="openModal('edit', {{ $dept }})" class="p-2 bg-gray-100 text-yellow-600 rounded-lg hover:bg-yellow-100 transition" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </button>
                            <form action="{{ route('admin.departments.destroy', $dept->id) }}" method="POST" onsubmit="return confirm('Hapus departemen?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 bg-gray-100 text-red-600 rounded-lg hover:bg-red-100 transition" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </div>

                    <h3 class="text-xl font-bold text-gray-800 mb-1 tracking-tight">{{ $dept->name }}</h3>
                    
                    <div class="flex items-center mt-3">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gray-50 text-gray-500 border border-gray-100 group-hover:bg-mna-light group-hover:text-mna-dark group-hover:border-mna-teal/30 transition-colors">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            {{ $dept->users_count }} Karyawan
                        </span>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center bg-white rounded-2xl border border-dashed border-gray-300">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                <h3 class="text-lg font-medium text-gray-900">Belum ada departemen</h3>
                <p class="text-gray-500">Silakan buat departemen pertama Anda.</p>
            </div>
        @endforelse
    </div>

    <div x-show="isModalOpen" style="display: none;" 
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm transition-opacity"
        x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        
        <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl p-0 overflow-hidden transform transition-all scale-100 relative" @click.away="isModalOpen = false">
            
            <div class="bg-mna-dark px-6 py-6 border-b border-gray-100 flex justify-between items-center relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-6 -mt-6"></div>
                <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-mna-accent to-transparent"></div>

                <h3 class="text-lg font-bold text-white relative z-10" x-text="isEditMode ? 'Edit Departemen' : 'Tambah Departemen'"></h3>
                <button @click="isModalOpen = false" class="text-white/70 hover:text-white relative z-10">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form :action="formAction" method="POST" class="p-8">
                @csrf
                <template x-if="isEditMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div class="mb-6">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">Nama Departemen</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <input type="text" name="name" x-model="formData.name" required placeholder="Contoh: Human Resource"
                            class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 text-gray-900 placeholder-gray-400 font-medium
                                   focus:outline-none focus:border-mna-teal focus:ring-4 focus:ring-mna-teal/10
                                   transition-all duration-200 bg-gray-50 focus:bg-white">
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" @click="isModalOpen = false" class="px-5 py-2.5 text-sm font-bold text-gray-500 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 hover:text-gray-700 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white bg-mna-dark rounded-xl hover:bg-mna-teal shadow-lg shadow-mna-dark/20 transition-all hover:-translate-y-0.5">
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
    function deptHandler() {
        return {
            isModalOpen: false,
            isEditMode: false,
            formAction: '',
            formData: { name: '' },

            openModal(type, dept = null) {
                this.isModalOpen = true;
                if (type === 'create') {
                    this.isEditMode = false;
                    this.formAction = "{{ route('admin.departments.store') }}";
                    this.formData.name = '';
                } else {
                    this.isEditMode = true;
                    this.formAction = `/admin/departments/${dept.id}`;
                    this.formData.name = dept.name;
                }
                setTimeout(() => { document.querySelector('input[name="name"]').focus(); }, 100);
            }
        }
    }
</script>
@endsection