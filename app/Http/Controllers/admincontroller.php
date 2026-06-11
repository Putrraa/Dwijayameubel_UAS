<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class admincontroller extends Controller
{
    // =========================
    // DATA PENGGUNA
    // =========================
    public function index()
    {
        $users = User::latest()->get();

        return view('barang.datpen', compact('users'));
    }

    // =========================
    // TAMBAH USER
    // =========================
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role'     => 'required|in:admin,kasir,customer',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'role'     => $request->role,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('datpen.index')
            ->with('success', 'Pengguna berhasil ditambahkan');
    }

    // =========================
    // EDIT USER
    // =========================
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'password' => 'nullable|min:6',
            'role'     => 'required|in:admin,kasir,customer',
        ]);

        $data = [
            'name'  => $request->name,
            'email' => $request->email,
            'role'  => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('datpen.index')
            ->with('success', 'Data pengguna berhasil diperbarui');
    }

    // =========================
    // HAPUS USER
    // =========================
    public function destroy($id)
    {
        if (auth()->id() == $id) {
            return redirect()->route('datpen.index')
                ->with('error', 'Akun yang sedang login tidak boleh dihapus');
        }

        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('datpen.index')
            ->with('success', 'Pengguna berhasil dihapus');
    }
}