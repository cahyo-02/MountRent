<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use App\Models\Transaksi; // Uncomment ini nanti jika ingin menghitung transaksi asli

class ProfilController extends Controller
{
    public function index()
    {
        return view('user.profil.index');
    }
}
