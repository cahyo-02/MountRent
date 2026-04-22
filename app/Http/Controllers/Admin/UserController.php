<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    // List semua user
    public function index()
    {
        $users = User::where('role', 'user')
            ->latest()
            ->paginate(10);

        return view('admin.user.index', compact('users'));
    }

    // Detail user + transaksi
    public function show(User $user)
    {
        $user->load('transaksis.details.barang');

        return view('admin.user.detail', compact('user'));
    }
}
