<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AksesAdminController extends Controller
{
    public function index()
    {
        $admins = User::where('role', 'admin')
            ->orderBy('name')
            ->get();

        return view('master.akses-admin.index', compact('admins'));
    }

    public function update(Request $request, User $user)
    {
        if ($user->role !== 'admin') {
            abort(403, 'Hak akses hanya bisa diberikan ke Admin.');
        }

        $user->update([
            'can_edit_peminjaman' => $request->boolean('can_edit_peminjaman'),
            'can_delete_peminjaman' => $request->boolean('can_delete_peminjaman'),
        ]);

        return redirect()
            ->route('master.akses-admin.index')
            ->with('success', 'Hak akses admin berhasil diperbarui.');
    }
}