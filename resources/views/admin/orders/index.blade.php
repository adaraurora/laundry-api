@extends('layouts.admin')
@section('title', 'Orders')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h2>Daftar Order</h2>
    <a href="{{ route('admin.orders.create') }}" class="btn btn-primary">+ Tambah</a>
</div>

<table class="table table-bordered table-hover">
    <thead class="table-dark">
        <tr><th>#</th><th>Customer</th><th>Layanan</th><th>Berat</th><th>Total Harga</th><th>Status</th><th>Aksi</th></tr>
    </thead>
    <tbody>
        @foreach($orders as $order)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $order->user->name ?? '-' }}</td>
            <td>{{ $order->layanan }}</td>
            <td>{{ $order->berat }} kg</td>
            <td>Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
            <td>
                <span class="badge bg-{{ $order->status === 'selesai' ? 'success' : ($order->status === 'proses' ? 'warning' : 'secondary') }}">
                    {{ ucfirst($order->status) }}
                </span>
            </td>
            <td>
                <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button onclick="return confirm('Yakin hapus?')" class="btn btn-sm btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $orders->links() }}
@endsection