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
            'alamat' => $request->alamat
        ]);

        return response()->json([
            'status' => true,
            'data' => $user
        ]);
    }

    public function update(Request $request, $id) {
        $user = User::find($id);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'alamat' => $request->alamat
        ]);

        return response()->json($user);
    }

    public function destroy($id) {
        User::find($id)->delete();
        return response()->json(['message' => 'deleted']);
    }
}
