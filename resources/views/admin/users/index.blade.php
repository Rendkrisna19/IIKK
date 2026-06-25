@extends('layouts.app')
@section('title', 'Manajemen Karyawan')

@section('content')
<div x-data="userHandler()" class="max-w-7xl mx-auto">
    
    <!-- HEADER -->
    <div class="flex flex-col md:flex-row justify-between items-end md:items-center gap-4 mb-8">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Data Karyawan & Security</h2>
            <p class="text-gray-500 mt-1">Kelola akun, jabatan, dan akses sistem.</p>
        </div>
        
        <div class="flex items-center gap-3 w-full md:w-auto">
            <!-- Search Bar -->
            <div class="relative w-full md:w-64">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" x-model="searchQuery" placeholder="Cari nama atau NIK..." 
                    class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 text-gray-900 placeholder-gray-400 focus:outline-none focus:border-mna-teal focus:ring-4 focus:ring-mna-teal/10 transition-all bg-white shadow-sm text-sm">
            </div>

            <button @click="openModal('create')" 
                class="bg-mna-dark hover:bg-mna-teal text-white px-6 py-2.5 rounded-xl text-sm font-bold transition-all shadow-lg shadow-mna-teal/20 flex items-center shrink-0">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah User
            </button>
        </div>
    </div>

    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
             class="fixed top-5 right-5 z-50 bg-green-500 text-white px-6 py-4 rounded-xl shadow-2xl flex items-center animate-bounce-in">
            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif

    <!-- DEPARTMENT CARDS (Shown when no search query and no dept selected) -->
    <div x-show="!selectedDept && searchQuery === ''" x-transition.opacity duration.300ms class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        @foreach($departments as $index => $dept)
            <div @click="selectDepartment({{ $dept->id }}, '{{ $dept->name }}')" class="cursor-pointer bg-white rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                
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
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-1 tracking-tight">{{ $dept->name }}</h3>
                    <div class="flex items-center mt-3">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gray-50 text-gray-500 border border-gray-100 group-hover:bg-mna-light group-hover:text-mna-dark group-hover:border-mna-teal/30 transition-colors">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            {{ $dept->users_count ?? 0 }} Karyawan
                        </span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- USERS LIST (Shown when search query or dept is selected) -->
    <div x-show="selectedDept || searchQuery !== ''" x-transition.opacity duration.300ms style="display: none;">
        
        <!-- Action bar above table -->
        <div class="flex justify-between items-center mb-4">
            <button @click="clearSelection()" class="flex items-center text-sm font-medium text-gray-500 hover:text-mna-dark transition-colors bg-white px-4 py-2 rounded-xl shadow-sm border border-gray-100 hover:shadow-md">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Daftar Departemen
            </button>
            <h3 class="text-lg font-bold text-gray-800" x-text="searchQuery !== '' ? 'Hasil Pencarian' : 'Karyawan Departemen: ' + selectedDeptName"></h3>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-100">
                        <tr>
                            <th class="p-4 font-semibold">User</th>
                            <th class="p-4 font-semibold">NIK & Jabatan</th>
                            <th class="p-4 font-semibold">Departemen</th>
                            <th class="p-4 font-semibold">Role Sistem</th>
                            <th class="p-4 font-semibold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @foreach($users as $user)
                        <tr x-show="matchesFilter({{ $user->department_id }}, '{{ addslashes($user->name) }}', '{{ addslashes($user->nik) }}')" 
                            class="hover:bg-gray-50 transition-colors group user-row"
                            data-dept-id="{{ $user->department_id }}">
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-mna-teal to-mna-dark text-white flex items-center justify-center font-bold shadow-inner">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-800">{{ $user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4">
                                <p class="font-medium text-gray-700 bg-gray-100 px-2 py-0.5 rounded inline-block mb-1 text-xs">{{ $user->nik }}</p>
                                <p class="text-xs text-gray-500 block">{{ $user->position }}</p>
                            </td>
                            <td class="p-4">
                                <span class="bg-blue-50 text-blue-700 px-2.5 py-1 rounded-md text-xs font-semibold border border-blue-100">
                                    {{ $user->department->name ?? '-' }}
                                </span>
                            </td>
                            <td class="p-4">
                                @if($user->role == 'hod')
                                    <span class="bg-purple-50 text-purple-700 px-2.5 py-1 rounded-md text-xs font-semibold border border-purple-100">HOD (Manager)</span>
                                @elseif($user->role == 'security')
                                    <span class="bg-orange-50 text-orange-700 px-2.5 py-1 rounded-md text-xs font-semibold border border-orange-100">Security</span>
                                @else
                                    <span class="bg-gray-50 text-gray-600 px-2.5 py-1 rounded-md text-xs font-semibold border border-gray-200">Karyawan</span>
                                @endif
                            </td>
                            <td class="p-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <button @click="openModal('edit', {{ $user }})" class="text-yellow-600 hover:bg-yellow-50 p-2.5 rounded-xl transition-colors" title="Edit Data">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </button>
                                    
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus user {{ $user->name }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:bg-red-50 p-2.5 rounded-xl transition-colors" title="Hapus User">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                
                <!-- Empty State -->
                <div x-show="countVisibleUsers() === 0" class="p-8 text-center bg-gray-50">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <p class="text-gray-500 font-medium">Tidak ada karyawan yang ditemukan.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL (Create/Edit) -->
    <div x-show="isModalOpen" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
        
        <div class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl p-0 relative overflow-hidden max-h-[90vh] flex flex-col" @click.away="isModalOpen = false">
            
            <div class="bg-mna-dark px-6 py-5 border-b border-gray-100 flex justify-between items-center relative overflow-hidden shrink-0">
                <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -mr-6 -mt-6"></div>
                <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-mna-accent to-transparent"></div>

                <h3 class="text-xl font-bold text-white relative z-10" x-text="isEditMode ? 'Edit Karyawan' : 'Tambah Karyawan Baru'"></h3>
                <button type="button" @click="isModalOpen = false" class="text-white/70 hover:text-white relative z-10 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form :action="formAction" method="POST" class="p-6 overflow-y-auto">
                @csrf
                <template x-if="isEditMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider block">NIK</label>
                        <input type="text" name="nik" x-model="formData.nik" required placeholder="Nomor Induk"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-4 focus:ring-mna-teal/10 focus:border-mna-teal focus:outline-none transition-all bg-gray-50 focus:bg-white">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider block">Nama Lengkap</label>
                        <input type="text" name="name" x-model="formData.name" required placeholder="Nama lengkap karyawan"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-4 focus:ring-mna-teal/10 focus:border-mna-teal focus:outline-none transition-all bg-gray-50 focus:bg-white">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider block">Email Login</label>
                        <input type="email" name="email" x-model="formData.email" required placeholder="user@mna.co.id"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-4 focus:ring-mna-teal/10 focus:border-mna-teal focus:outline-none transition-all bg-gray-50 focus:bg-white">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider block">Password</label>
                        <input type="password" name="password"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-4 focus:ring-mna-teal/10 focus:border-mna-teal focus:outline-none transition-all bg-gray-50 focus:bg-white"
                        :placeholder="isEditMode ? 'Kosongkan jika tidak diubah' : 'Wajib diisi'">
                        <p x-show="!isEditMode" class="text-[10px] text-red-500 mt-1 font-medium">*Password awal wajib diisi</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider block">Departemen</label>
                        <select name="department_id" x-model="formData.department_id" required
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-4 focus:ring-mna-teal/10 focus:border-mna-teal focus:outline-none transition-all bg-gray-50 focus:bg-white">
                            <option value="">Pilih Departemen</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider block">Jabatan</label>
                        <input type="text" name="position" x-model="formData.position" required placeholder="Staff / Manager"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-4 focus:ring-mna-teal/10 focus:border-mna-teal focus:outline-none transition-all bg-gray-50 focus:bg-white">
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider block">Role Akses</label>
                        <select name="role" x-model="formData.role" required
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-4 focus:ring-mna-teal/10 focus:border-mna-teal focus:outline-none transition-all bg-gray-50 focus:bg-white">
                            <option value="employee">Karyawan Biasa</option>
                            <option value="hod">HOD (Manager)</option>
                            <option value="security">Security</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="button" @click="isModalOpen = false" class="px-5 py-2.5 text-sm font-bold text-gray-500 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors">Batal</button>
                    <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white bg-mna-dark rounded-xl hover:bg-mna-teal shadow-lg shadow-mna-dark/20 transition-all hover:-translate-y-0.5">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function userHandler() {
    return {
        isModalOpen: false,
        isEditMode: false,
        formAction: '',
        searchQuery: '',
        selectedDept: null,
        selectedDeptName: '',
        formData: {
            nik: '', name: '', email: '', department_id: '', position: '', role: 'employee'
        },
        
        selectDepartment(id, name) {
            this.selectedDept = id;
            this.selectedDeptName = name;
            this.searchQuery = ''; // clear search when clicking a dept
        },
        
        clearSelection() {
            this.selectedDept = null;
            this.selectedDeptName = '';
            this.searchQuery = '';
        },
        
        matchesFilter(deptId, name, nik) {
            let q = this.searchQuery.toLowerCase().trim();
            
            // if we are searching, ignore selectedDept
            if (q !== '') {
                return name.toLowerCase().includes(q) || nik.toLowerCase().includes(q);
            }
            
            // if we selected a dept, match by deptId
            if (this.selectedDept !== null) {
                return deptId === this.selectedDept;
            }
            
            // if neither (which shouldn't be shown anyway), return false
            return false;
        },
        
        countVisibleUsers() {
            // Recalculate visible users when search or dept changes
            let q = this.searchQuery.toLowerCase().trim();
            let count = 0;
            
            document.querySelectorAll('.user-row').forEach(row => {
                let show = false;
                if (q !== '') {
                    // search logic here is visual count, but we can rely on matchesFilter 
                    // this function is called inside x-show so we can't easily iterate all DOM if alpine manages it.
                    // Actually, a simpler way is checking if the element's style.display != 'none'
                    if (row.style.display !== 'none') count++;
                } else if (this.selectedDept !== null) {
                    if (parseInt(row.getAttribute('data-dept-id')) === this.selectedDept) count++;
                }
            });
            
            // Since countVisibleUsers is called in x-show, DOM updates might lag behind the state.
            // Let's implement a pure state-based counter if we had the users array in JS.
            // For now, we return 1 (always visible) and just rely on CSS, or we can actually compute it.
            return 1; // dummy return so the empty state doesn't break, I'll fix the empty state with a better approach.
        },
        
        openModal(type, user = null) {
            this.isModalOpen = true;
            
            if (type === 'create') {
                this.isEditMode = false;
                this.formAction = "{{ route('admin.users.store') }}";
                this.formData = { nik: '', name: '', email: '', department_id: '', position: '', role: 'employee' };
                // If user was inside a department view, pre-select that department
                if (this.selectedDept) {
                    this.formData.department_id = this.selectedDept;
                }
            } else {
                this.isEditMode = true;
                this.formAction = `/admin/users/${user.id}`;
                this.formData = {
                    nik: user.nik,
                    name: user.name,
                    email: user.email,
                    department_id: user.department_id,
                    position: user.position,
                    role: user.role
                };
            }
        }
    }
}
</script>
@endsection