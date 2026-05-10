@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<h2 class="mb-4">Dashboard</h2>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5>Total Orders</h5>
                <h2>{{ $totalOrders }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5>Total Services</h5>
                <h2>{{ $totalServices }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h5>Total Users</h5>
                <h2>{{ $totalUsers }}</h2>
            </div>
        </div>
    </div>
</div>

<h5>Order Terbaru</h5>
<table class="table table-bordered">
    <thead>
        <tr><th>#</th><th>User</th><th>Status</th><th>Tanggal</th></tr>
    </thead>
    <tbody>
        @foreach($recentOrders as $order)
        <tr>
            <td>{{ $order->id }}</td>
            <td>{{ $order->user->name ?? '-' }}</td>
            <td>
                <span class="badge bg-{{ $order->status === 'selesai' ? 'success' : ($order->status === 'proses' ? 'warning' : 'secondary') }}">
                    {{ ucfirst($order->status) }}
                </span>
            </td>
            <td>{{ $order->created_at->format('d/m/Y') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection