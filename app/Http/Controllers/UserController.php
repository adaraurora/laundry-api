<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index() {
        return User::all();
    }

    public function show($id) {
        return User::find($id);
    }

    public function store(Request $request) {
        $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'alamat' => $request->alamat,
            'role' => $request->role
        ]);

        return response()->json([
            'status' => true,
            'data' => $user
        ]);
    }

    public function update(Request $request, $id) {
        $user = User::find($id);

        $request->validate([
        'name' => 'required',
        'email' => 'required|email'
        ]);

        if (!$user) {
            return response()->json([
                'message' => 'User tidak ditemukan'
            ], 404);
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'alamat' => $request->alamat
        ]);

        return response()->json($user);
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
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'email tidak ditemukan'
            ]);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'password salah'
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'login berhasil',
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
}


