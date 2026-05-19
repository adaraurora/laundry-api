<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\WalletTransaction;
use Illuminate\Support\Str;
use Carbon\Carbon;

class OrderController extends Controller
{
    // Melihat semua daftar pesanan
    public function index() {
        return Order::with(['user', 'service'])->latest()->get();
    }

    // Membuat pesanan baru (Otomatis hitung total harga)
    public function store(Request $request) 
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'berat' => 'required|numeric|min:1',
            'alamat' => 'required',
            'metode_pembayaran' => 'nullable'
        ]);

        $user = User::find($request->user_id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User tidak ditemukan'
            ], 404);
        }

        $service = Service::find($request->service_id);

        if (!$service) {
            return response()->json([
                'status' => false,
                'message' => 'Layanan tidak ditemukan'
            ], 404);
        }

        $berat = (float) $request->berat;
        $harga = (int) $service->harga;

        $subtotal = $berat * $harga;
        $ongkir = 10000;
        $diskon = 0;
        $total = (int) ($subtotal + $ongkir - $diskon);

        $metodePembayaran = $request->metode_pembayaran ?? 'Saldo Dompet';
        $statusPembayaran = 'belum_bayar';

        if ($request->metode_pembayaran === 'Saldo Dompet') {
            if ($user->saldo < $total) {
                return response()->json([
                    'status' => false,
                    'message' => 'Saldo tidak cukup'
                ], 400);
            }

            $user->saldo -= $total;
            $user->save();

            $statusPembayaran = 'sudah_bayar';
        }

        $order = Order::create([
            'kode_order' => 'ORD-' . rand(10000, 99999),
            'user_id' => $user->id,
            'service_id' => $service->id,
            'layanan' => $service->nama_layanan,
            'berat' => $berat,
            'catatan' => $request->catatan,
            'alamat' => $request->alamat,
            'total_harga' => $total,
            'ongkir' => $ongkir,
            'diskon' => $diskon,
            'metode_pembayaran' => $metodePembayaran,
            'status_pembayaran' => $statusPembayaran,
            'status' => 'dijemput',
            'estimasi_selesai' => now()->addDay()
        ]);

        if ($request->metode_pembayaran === 'Saldo Dompet') {
            WalletTransaction::create([
                'user_id' => $user->id,
                'type' => 'payment',
                'amount' => $total,
                'description' => 'Pembayaran pesanan #' . $order->kode_order
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Pesanan berhasil dibuat',
            'data' => $order
        ]);
    }

    // Melihat detail satu pesanan secara spesifik
    public function show($id) 
    {
        $order = Order::with(['user', 'service'])->find($id);
    
        if (!$order) {
            return response()->json([
                'status' => false,
                'message' => 'Pesanan tidak ditemukan'
            ], 404);
        }
    
        return response()->json([
            'status' => true,
            'message' => 'Detail pesanan berhasil diambil',
            'data' => $order
        ]);
    }

    // Update data order secara umum
    public function update(Request $request, $id) 
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'status' => false,
                'message' => 'Pesanan tidak ditemukan'
            ], 404);
        }

        $order->update([
            'berat' => $request->berat ?? $order->berat,
            'catatan' => $request->catatan ?? $order->catatan,
            'alamat' => $request->alamat ?? $order->alamat,
            'metode_pembayaran' => $request->metode_pembayaran ?? $order->metode_pembayaran
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Pesanan berhasil diupdate',
            'data' => $order
        ]);
    }

    // Update STATUS pesanan (KHUSUS ADMIN)
    public function updateStatus(Request $request, $id) 
    {
        $request->validate([
            'admin_id' => 'required|exists:users,id',
            'status' => 'required|in:dijemput,dicuci,setrika,dikirim,selesai,dibatalkan'
        ]);

        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'status' => false,
                'message' => 'Pesanan tidak ditemukan'
            ], 404);
        }

        $admin = User::find($request->admin_id);

        if (!$admin || $admin->role !== 'admin') {
            return response()->json([
                'status' => false,
                'message' => 'Akses ditolak. Hanya admin yang boleh mengubah status.'
            ], 403);
        }

        $oldStatus = $order->status;

        $order->status = $request->status;
        $order->save();

        if ($request->status === 'selesai' && $oldStatus !== 'selesai') {
            $user = User::find($order->user_id);

            if ($user) {
                $poinBaru = floor($order->total_harga / 10000);
                $user->poin += $poinBaru;
                $user->save();
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Status berhasil diperbarui',
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
        $orders = Order::where('user_id', $id)
            ->latest()
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Data order user berhasil diambil',
            'data' => $orders
        ]);
    }
}