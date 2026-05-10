@extends('layouts.admin')
@section('title', 'Edit Order')

@section('content')
<h2 class="mb-4">Edit Order</h2>
<form action="{{ route('admin.orders.update', $order) }}" method="POST">
    @csrf @method('PUT')
    <div class="mb-3">
        <label>Customer</label>
        <select name="user_id" class="form-select">
            @foreach($users as $user)
                <option value="{{ $user->id }}" {{ $order->user_id == $user->id ? 'selected' : '' }}>
                    {{ $user->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Layanan</label>
        <select name="layanan" class="form-select">
            @foreach($services as $service)
                <option value="{{ $service->nama_layanan }}"
                    {{ $order->layanan == $service->nama_layanan ? 'selected' : '' }}>
                    {{ $service->nama_layanan }} - Rp {{ number_format($service->harga,0,',','.') }}/{{ $service->satuan }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Berat (kg)</label>
        <input type="number" name="berat" step="0.1" min="1" class="form-control"
               value="{{ old('berat', $order->berat) }}">
    </div>
    <div class="mb-3">
        <label>Status</label>
        <select name="status" class="form-select">
            @foreach(['pending','proses','selesai'] as $s)
                <option value="{{ $s }}" {{ $order->status == $s ? 'selected' : '' }}>
                    {{ ucfirst($s) }}
                </option>
            @endforeach
        </select>
    </div>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Batal</a>
    <button type="submit" class="btn btn-primary">Update</button>
</form>
@endsection