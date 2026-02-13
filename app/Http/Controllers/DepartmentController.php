<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DepartmentController extends Controller
{
    public function index()
    {
        // Ambil data departemen + hitung jumlah karyawan di dalamnya
        $departments = Department::withCount('users')->latest()->get();
        return view('admin.departments.index', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:departments,name'
        ], [
            'name.unique' => 'Nama departemen ini sudah ada.'
        ]);

        Department::create($request->all());

        return redirect()->back()->with('success', 'Departemen berhasil ditambahkan.');
    }

    public function update(Request $request, Department $department)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('departments')->ignore($department->id)]
        ]);

        $department->update($request->all());

        return redirect()->back()->with('success', 'Nama departemen diperbarui.');
    }

    public function destroy(Department $department)
    {
        // Cek apakah masih ada karyawan di departemen ini?
        if($department->users()->count() > 0) {
            return redirect()->back()->with('error', 'Gagal hapus! Masih ada karyawan di departemen ini.');
        }

        $department->delete();
        return redirect()->back()->with('success', 'Departemen berhasil dihapus.');
    }
}