<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')->latest()->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function create()
    {
        $users    = User::all();
        $services = Service::all();
        return view('admin.orders.create', compact('users', 'services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'layanan' => 'required|string',
            'berat'   => 'required|numeric|min:1',
            'status'  => 'required|in:pending,proses,selesai',
        ]);

        // Hitung total_harga otomatis
        $service     = Service::where('nama_layanan', $request->layanan)->first();
        $total_harga = $service ? $service->harga * $request->berat : 0;

        Order::create([
            'user_id'     => $request->user_id,
            'layanan'     => $request->layanan,
            'berat'       => $request->berat,
            'total_harga' => $total_harga,
            'status'      => $request->status,
        ]);

        return redirect()->route('admin.orders.index')
                         ->with('success', 'Order berhasil ditambahkan!');
    }

    public function edit(Order $order)
    {
        $users    = User::all();
        $services = Service::all();
        return view('admin.orders.edit', compact('order', 'users', 'services'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'layanan' => 'required|string',
            'berat'   => 'required|numeric|min:1',
            'status'  => 'required|in:pending,proses,selesai',
        ]);

        $service     = Service::where('nama_layanan', $request->layanan)->first();
        $total_harga = $service ? $service->harga * $request->berat : $order->total_harga;

        $order->update([
            'user_id'     => $request->user_id,
            'layanan'     => $request->layanan,
            'berat'       => $request->berat,
            'total_harga' => $total_harga,
            'status'      => $request->status,
        ]);

        return redirect()->route('admin.orders.index')
                         ->with('success', 'Order berhasil diupdate!');
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('admin.orders.index')
                         ->with('success', 'Order berhasil dihapus!');
    }
}