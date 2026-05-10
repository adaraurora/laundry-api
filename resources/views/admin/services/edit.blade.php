@extends('layouts.admin')
@section('title', 'Edit Layanan')

@section('content')
<h2 class="mb-4">Edit Layanan</h2>
<form action="{{ route('admin.services.update', $service) }}" method="POST">
    @csrf @method('PUT')
    <div class="mb-3">
        <label>Nama Layanan</label>
        <input type="text" name="nama_layanan" class="form-control"
               value="{{ old('nama_layanan', $service->nama_layanan) }}">
    </div>
    <div class="mb-3">
        <label>Deskripsi</label>
        <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $service->deskripsi) }}</textarea>
    </div>
    <div class="mb-3">
        <label>Harga (Rp)</label>
        <input type="number" name="harga" class="form-control"
               value="{{ old('harga', $service->harga) }}">
    </div>
    <div class="mb-3">
        <label>Satuan</label>
        <input type="text" name="satuan" class="form-control"
               value="{{ old('satuan', $service->satuan) }}">
    </div>
    <a href="{{ route('admin.services.index') }}" class="btn btn-secondary">Batal</a>
    <button type="submit" class="btn btn-primary">Update</button>
</form>
@endsection