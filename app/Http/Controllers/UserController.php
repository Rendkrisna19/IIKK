<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        // Kirim data Users DAN Departments ke view index
        $users = User::with('department')->where('role', '!=', 'admin')->latest()->get();
        $departments = Department::all(); 
        
        return view('admin.users.index', compact('users', 'departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'nik' => 'required|unique:users',
            'department_id' => 'required',
            'role' => 'required',
            'position' => 'required',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'nik' => $request->nik,
            'department_id' => $request->department_id,
            'role' => $request->role,
            'position' => $request->position,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('success', 'Karyawan berhasil ditambahkan.');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'nik' => ['required', Rule::unique('users')->ignore($user->id)],
            'department_id' => 'required',
            'role' => 'required',
            'position' => 'required',
            'password' => 'nullable|min:6', // Password boleh kosong saat edit
        ]);

        $data = $request->except(['password', '_token', '_method']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->back()->with('success', 'Data karyawan diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->back()->with('success', 'Karyawan dihapus.');
    }
}