<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Melihat semua daftar pesanan
    public function index() {
        return Order::with('user')->get();
    }

    // Membuat pesanan baru (Otomatis hitung total harga)
    public function store(Request $request) {
        $harga_per_kg = 5000;
        $total = $request->berat * $harga_per_kg;

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'layanan' => 'required',
            'berat' => 'required|numeric|min:1'
        ]);

        $order = Order::create([
            'user_id' => $request->user_id,
            'layanan' => $request->layanan,
            'berat' => $request->berat,
            'total_harga' => $total,
            'status' => 'proses'
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Pesanan berhasil dibuat',
            'data' => $order
        ]);
    }

    // Melihat detail satu pesanan secara spesifik
    public function show($id) {
        $order = Order::with('user')->find($id);
        
        if (!$order) {
            return response()->json(['message' => 'Pesanan tidak ditemukan'], 404);
        }
        
        return response()->json($order);
    }

    // Update data order secara umum
    public function update(Request $request, $id) {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['message' => 'Pesanan tidak ditemukan'], 404);
        }
        $order->update([
            'layanan' => $request->layanan,
            'berat' => $request->berat
        ]);
        return response()->json($order);
    }

    // Update STATUS pesanan (KHUSUS ADMIN)
    public function updateStatus(Request $request, $id) {
        $order = Order::find($id);

        if (!$order) {
            return response()->json(['message' => 'Pesanan tidak ditemukan'], 404);
        }

        // --- LOGIKA KEAMANAN ADMIN ---
        // Mencari data user yang mencoba mengganti status
        $userMelakukanAksi = User::find($request->admin_id);

        // Cek: Apakah usernya ada dan apakah dia seorang admin?
        if (!$userMelakukanAksi || $userMelakukanAksi->role !== 'admin') {
            return response()->json([
                'status' => 'error',
                'message' => 'Akses Ditolak! Hanya Admin yang boleh mengubah status pesanan.'
            ], 403); // Error 403: Forbidden
        }

        // Jika lolos pengecekan, status diupdate
        $order->status = $request->status; // Contoh: 'selesai' atau 'diambil'
        $order->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Status berhasil diperbarui oleh Admin: ' . $userMelakukanAksi->name,
            'data' => $order
        ]);
    }

    // Menghapus pesanan
    public function destroy($id) {
        $order = Order::find($id);
        if ($order) {
            $order->delete();
            return response()->json(['message' => 'Pesanan berhasil dihapus']);
        }
        return response()->json(['message' => 'Pesanan tidak ditemukan'], 404);
    }

    public function byUser($id)
    {
        $orders = Order::where('user_id', $id)->get();

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'layanan' => 'required',
            'berat' => 'required|numeric|min:1'
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Data order user berhasil diambil',
            'data' => $orders
        ]);
    }
}