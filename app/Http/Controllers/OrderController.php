<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Models\Service;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'service'])
            ->latest()
            ->get();

        return response()->json($orders);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'berat' => 'required|numeric|min:1',
            'alamat' => 'required|string',
            'catatan' => 'nullable|string',
            'metode_pembayaran' => 'nullable|string'
        ]);

        $user = User::find($request->user_id);
        $service = Service::find($request->service_id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User tidak ditemukan'
            ], 404);
        }

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

        $metodePembayaran = $request->metode_pembayaran ?? 'wallet';

        if (!in_array($metodePembayaran, ['wallet', 'transfer', 'ewallet', 'cash'])) {
            $metodePembayaran = 'wallet';
        }

        $statusPembayaran = 'belum_bayar';

        if ($metodePembayaran === 'wallet') {
            if ((int) $user->saldo < $total) {
                return response()->json([
                    'status' => false,
                    'message' => 'Saldo tidak cukup'
                ], 400);
            }

            $user->saldo = (int) $user->saldo - $total;
            $user->save();

            $statusPembayaran = 'sudah_bayar';
        }

        if ($metodePembayaran === 'transfer' || $metodePembayaran === 'ewallet') {
            $statusPembayaran = 'menunggu_pembayaran';
        }

        $order = Order::create([
            'kode_order' => 'ORD-' . random_int(10000, 99999),
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

        if ($metodePembayaran === 'wallet') {
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
        ], 201);
    }

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
                $user->poin = (int) $user->poin + $poinBaru;
                $user->save();
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Status berhasil diperbarui',
            'data' => $order
        ]);
    }

    public function destroy($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'status' => false,
                'message' => 'Pesanan tidak ditemukan'
            ], 404);
        }

        $order->delete();

        return response()->json([
            'status' => true,
            'message' => 'Pesanan berhasil dihapus'
        ]);
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