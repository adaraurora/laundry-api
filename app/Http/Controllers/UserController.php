<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\WalletTransaction;

class UserController extends Controller
{
    public function index() {
        return User::all();
    }

    public function show($id) {
        return User::find($id);
    }

    public function store(Request $request) 
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required|unique:users,phone',
            'email' => 'nullable|email|unique:users,email',
            'password' => 'required|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'alamat' => $request->alamat,
            'role' => $request->role ?? 'user',
            'saldo' => 750000,
            'poin' => 1200
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Registrasi berhasil',
            'data' => $user
        ]);
    }

    public function destroy($id) {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'User tidak ditemukan'
            ], 404);
        }

        $user->delete();
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->login)
            ->orWhere('phone', $request->login)
            ->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Akun tidak ditemukan'
            ], 404);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Password salah'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Login berhasil',
            'token' => $token,
            'data' => $user
        ]);
    }

    public function profile($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $user
        ]);
    }

    public function topUp(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|integer|min:1000'
        ]);

        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User tidak ditemukan'
            ], 404);
        }

        $amount = (int) $request->amount;
        $saldoSekarang = (int) ($user->saldo ?? 0);

        $user->saldo = $saldoSekarang + $amount;
        $user->save();

        WalletTransaction::create([
            'user_id' => $user->id,
            'type' => 'topup',
            'amount' => $request->amount,
            'description' => 'Top up saldo dompet'
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Top up berhasil',
            'data' => $user
        ]);
    }
}


