<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;


class OrderController extends Controller
{
    public function index() {
        return Order::with('user')->get();
    }

    public function store(Request $request) {
        $harga_per_kg = 5000;

        $total = $request->berat * $harga_per_kg;

        $order = Order::create([
            'user_id' => $request->user_id,
            'layanan' => $request->layanan,
            'berat' => $request->berat,
            'total_harga' => $total,
            'status' => 'proses'
        ]);

        return response()->json($order);
    }

    public function show($id) {
        return Order::with('user')->find($id);
    }

    public function update(Request $request, $id) {
        $order = Order::find($id);
        $order->update($request->all());
        return $order;
    }

    public function updateStatus(Request $request, $id) {

        $order = Order::find($id);

        $order->status = $request->status;
        $order->save();

        return response()->json($order);
    }

    public function destroy($id) {
            Order::find($id)->delete();
        return response()->json(['message' => 'deleted']);
    }
}
