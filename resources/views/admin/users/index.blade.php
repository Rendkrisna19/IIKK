@extends('layouts.app')
@section('title', 'Manajemen Karyawan')

@section('content')
<div x-data="userHandler()" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    
    <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Data Karyawan & Security</h2>
            <p class="text-sm text-gray-500">Kelola akun, jabatan, dan akses sistem.</p>
        </div>
        <button @click="openModal('create')" 
            class="bg-mna-dark hover:bg-mna-teal text-white px-5 py-2.5 rounded-lg text-sm font-medium transition-all shadow-lg shadow-mna-teal/30 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah User Baru
        </button>
    </div>

    @if(session('success'))
        <div class="bg-green-50 text-green-700 p-4 text-sm border-l-4 border-green-500 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
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
                <tr class="hover:bg-gray-50 transition-colors group">
                    <td class="p-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-mna-teal text-white flex items-center justify-center font-bold text-xs">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-bold text-gray-800">{{ $user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="p-4">
                        <p class="font-medium text-gray-700">{{ $user->nik }}</p>
                        <p class="text-xs text-gray-500">{{ $user->position }}</p>
                    </td>
                    <td class="p-4">
                        <span class="bg-blue-50 text-blue-700 px-2 py-1 rounded text-xs font-semibold">
                            {{ $user->department->name ?? '-' }}
                        </span>
                    </td>
                    <td class="p-4">
                        @if($user->role == 'hod')
                            <span class="bg-purple-50 text-purple-700 px-2 py-1 rounded text-xs font-semibold">HOD (Manager)</span>
                        @elseif($user->role == 'security')
                            <span class="bg-orange-50 text-orange-700 px-2 py-1 rounded text-xs font-semibold">Security</span>
                        @else
                            <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs font-semibold">Karyawan</span>
                        @endif
                    </td>
                    <td class="p-4 text-right">
                        <div class="flex justify-end gap-2">
                            <button @click="openModal('edit', {{ $user }})" class="text-yellow-600 hover:bg-yellow-50 p-2 rounded-lg transition-colors" title="Edit Data">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                            
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus user {{ $user->name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:bg-red-50 p-2 rounded-lg transition-colors" title="Hapus User">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div x-show="isModalOpen" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        
        <div class="bg-white w-full max-w-2xl rounded-xl shadow-2xl p-6 relative overflow-y-auto max-h-[90vh]" @click.away="isModalOpen = false">
            <div class="flex justify-between items-center mb-6 border-b pb-4">
                <h3 class="text-xl font-bold text-gray-800" x-text="isEditMode ? 'Edit User' : 'Tambah User Baru'"></h3>
                <button @click="isModalOpen = false" class="text-gray-400 hover:text-red-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form :action="formAction" method="POST">
                @csrf
                <template x-if="isEditMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                    <div>
                        <label class="text-sm font-medium text-gray-700 mb-1 block">NIK</label>
                        <input type="text" name="nik" x-model="formData.nik" required placeholder="Nomor Induk"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-mna-teal focus:border-mna-teal focus:outline-none transition-colors">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700 mb-1 block">Nama Lengkap</label>
                        <input type="text" name="name" x-model="formData.name" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-mna-teal focus:border-mna-teal focus:outline-none transition-colors">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700 mb-1 block">Email Login</label>
                        <input type="email" name="email" x-model="formData.email" required placeholder="user@mna.co.id"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-mna-teal focus:border-mna-teal focus:outline-none transition-colors">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700 mb-1 block">Password</label>
                        <input type="password" name="password"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-mna-teal focus:border-mna-teal focus:outline-none transition-colors"
                        :placeholder="isEditMode ? 'Kosongkan jika tidak ubah' : 'Wajib diisi'">
                        <p x-show="!isEditMode" class="text-xs text-red-500 mt-1">*Password awal wajib diisi</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
                    <div>
                        <label class="text-sm font-medium text-gray-700 mb-1 block">Departemen</label>
                        <select name="department_id" x-model="formData.department_id" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-mna-teal focus:border-mna-teal focus:outline-none bg-white">
                            <option value="">Pilih...</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                        <p class="text-[10px] text-gray-400 mt-1">Security pilih dept. Umum/GA</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-700 mb-1 block">Jabatan</label>
                        <input type="text" name="position" x-model="formData.position" required placeholder="Contoh: Staff / Satpam"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-mna-teal focus:border-mna-teal focus:outline-none">
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-700 mb-1 block">Role Sistem</label>
                        <select name="role" x-model="formData.role" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-mna-teal focus:border-mna-teal focus:outline-none bg-white">
                            <option value="employee">Karyawan (Default)</option>
                            <option value="hod">HOD (Manager)</option>
                            <option value="security">Security (Pos Jaga)</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="button" @click="isModalOpen = false" class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition">Batal</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-mna-dark rounded-lg hover:bg-mna-teal shadow transition">Simpan Data</button>
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
        formData: {
            nik: '', name: '', email: '', department_id: '', position: '', role: 'employee'
        },
        
        openModal(type, user = null) {
            this.isModalOpen = true;
            
            if (type === 'create') {
                this.isEditMode = false;
                this.formAction = "{{ route('admin.users.store') }}";
                this.formData = { nik: '', name: '', email: '', department_id: '', position: '', role: 'employee' };
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