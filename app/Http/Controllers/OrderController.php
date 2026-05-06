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
        $order = Order::create($request->all());
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

    public function destroy($id) {
        Order::find($id)->delete();
        return response()->json(['message' => 'deleted']);
    }
}
