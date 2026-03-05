@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')

@if(session('success'))
    <div class="mb-6 p-4 rounded-xl bg-teal-50 border border-teal-200 text-teal-700 flex items-start gap-3 shadow-sm transition-all" x-data="{ show: true }" x-show="show">
        <i class="fas fa-check-circle text-xl mt-0.5"></i>
        <div class="flex-1">
            <h3 class="font-bold text-sm">Berhasil!</h3>
            <p class="text-xs mt-1">{{ session('success') }}</p>
        </div>
        <button @click="show = false" class="text-teal-400 hover:text-teal-700"><i class="fas fa-times"></i></button>
    </div>
@endif

@if($errors->any())
    <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 flex items-start gap-3 shadow-sm transition-all" x-data="{ show: true }" x-show="show">
        <i class="fas fa-exclamation-circle text-xl mt-0.5"></i>
        <div class="flex-1">
            <h3 class="font-bold text-sm">Terjadi Kesalahan!</h3>
            <ul class="text-xs mt-1 list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <button @click="show = false" class="text-red-400 hover:text-red-700"><i class="fas fa-times"></i></button>
    </div>
@endif
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    
    <div class="xl:col-span-1">
        <div class="bg-white rounded-2xl shadow-card border border-gray-100 overflow-hidden">
            <div class="h-24 bg-gradient-to-r from-mna-dark to-mna-teal"></div>
            <div class="px-6 pb-6 relative">
                <div class="flex justify-center -mt-12 mb-4">
                    @if($user->profile_photo)
                        <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Profile" class="h-24 w-24 rounded-full object-cover border-4 border-white shadow-md bg-white">
                    @else
                        <div class="h-24 w-24 rounded-full border-4 border-white shadow-md bg-gradient-to-br from-mna-teal to-mna-dark text-white flex items-center justify-center font-bold text-3xl">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                    @endif
                </div>
                <div class="text-center mb-6">
                    <h3 class="text-xl font-extrabold text-gray-800">{{ $user->name }}</h3>
                    <p class="text-sm font-semibold text-mna-accent uppercase tracking-wider mt-1">{{ $user->position ?? 'Karyawan' }}</p>
                    <span class="inline-block mt-3 px-3 py-1 bg-gray-100 text-gray-600 text-xs font-bold rounded-full uppercase tracking-widest">{{ $user->role }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="xl:col-span-2 space-y-6">
        <div class="bg-white rounded-2xl shadow-card border border-gray-100 p-6 sm:p-8">
            <div class="mb-6 flex items-center gap-3 border-b border-gray-100 pb-4">
                <div class="h-10 w-10 rounded-xl bg-mna-light flex items-center justify-center text-mna-teal">
                    <i class="fas fa-user-edit text-lg"></i>
                </div>
                <div>
                    <h2 class="text-lg font-extrabold text-gray-800">Informasi Pribadi</h2>
                    <p class="text-xs text-gray-500 font-medium">Perbarui detail profil dan informasi login Anda.</p>
                </div>
            </div>

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nomor Induk Karyawan (NIK)</label>
                        <input type="text" name="nik" value="{{ old('nik', $user->nik) }}" class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-xl focus:ring-2 focus:ring-mna-teal focus:border-mna-teal block p-3 shadow-sm transition-all @error('nik') border-red-500 ring-red-100 @enderror">
                        @error('nik') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-xl focus:ring-2 focus:ring-mna-teal focus:border-mna-teal block p-3 shadow-sm transition-all @error('name') border-red-500 ring-red-100 @enderror">
                        @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Alamat Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-xl focus:ring-2 focus:ring-mna-teal focus:border-mna-teal block p-3 shadow-sm transition-all @error('email') border-red-500 ring-red-100 @enderror">
                        @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2" x-data="{ photoName: null, photoPreview: null }">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Ubah Foto Profil</label>
                        
                        <div class="flex items-center gap-4">
                            <div class="shrink-0" x-show="photoPreview" style="display: none;">
                                <img :src="photoPreview" class="h-24 w-24 object-cover rounded-2xl border border-gray-200 shadow-sm">
                            </div>

                            <div class="flex-1">
                                <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer bg-gray-50 hover:bg-mna-light hover:border-mna-teal transition-all group">
                                    
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6" x-show="!photoPreview">
                                        <i class="fas fa-cloud-upload-alt text-2xl text-gray-400 group-hover:text-mna-teal mb-2"></i>
                                        <p class="mb-1 text-sm text-gray-500"><span class="font-semibold text-mna-teal">Klik untuk unggah</span> atau seret file</p>
                                        <p class="text-xs text-gray-400">PNG, JPG atau JPEG (Max: 2MB)</p>
                                    </div>

                                    <div class="flex flex-col items-center justify-center pt-5 pb-6" x-show="photoPreview" style="display: none;">
                                        <i class="fas fa-check-circle text-3xl text-mna-teal mb-2"></i>
                                        <p class="mt-2 text-sm font-bold text-gray-700" x-text="photoName"></p>
                                        <p class="text-xs text-gray-500 mt-1">Klik kotak ini untuk mengganti foto</p>
                                    </div>

                                    <input type="file" name="photo" class="hidden" accept="image/*" 
                                        x-ref="photo"
                                        x-on:change="
                                            photoName = $refs.photo.files[0].name;
                                            const reader = new FileReader();
                                            reader.onload = (e) => { photoPreview = e.target.result; };
                                            reader.readAsDataURL($refs.photo.files[0]);
                                        "
                                    />
                                </label>
                            </div>
                        </div>
                        @error('photo') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-100">
                    <h3 class="text-md font-bold text-gray-800 mb-4">Ganti Password <span class="text-xs font-normal text-gray-400 ml-2">(Kosongkan jika tidak ingin mengubah)</span></h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Password Baru</label>
                            <input type="password" name="password" placeholder="••••••••" class="w-full bg-gray-50 border border-gray-200 text-gray-800 text-sm rounded-xl focus:ring-2 focus:ring-mna-teal focus:border-mna-teal block p-3 shadow-sm transition-all">
                            @error('password') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end">
                    <button type="submit" class="bg-mna-teal hover:bg-mna-dark text-white font-bold py-3 px-8 rounded-xl shadow-md shadow-mna-teal/20 transition-all flex items-center gap-2">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection