<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'nik' => 'required|unique:users,nik,' . $user->id,
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            // Hapus 'confirmed' di bawah ini
            'password' => 'nullable|min:8', 
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->nik = $request->nik;

        // Handle Upload Foto
        if ($request->hasFile('photo')) {
            if ($user->profile_photo) {
                Storage::delete('public/' . $user->profile_photo);
            }
            $path = $request->file('photo')->store('profile_photos', 'public');
            $user->profile_photo = $path;
        }

        // Handle Ganti Password (Hanya dieksekusi jika kolom password diisi)
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}