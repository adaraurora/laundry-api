@extends('layouts.admin')
@section('title', 'Tambah Layanan')

@section('content')
<h2 class="mb-4">Tambah Layanan</h2>
<form action="{{ route('admin.services.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label>Nama Layanan</label>
        <input type="text" name="nama_layanan" class="form-control @error('nama_layanan') is-invalid @enderror"
               value="{{ old('nama_layanan') }}">
        @error('nama_layanan')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
        <label>Deskripsi</label>
        <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi') }}</textarea>
    </div>
    <div class="mb-3">
        <label>Harga (Rp)</label>
        <input type="number" name="harga" class="form-control @error('harga') is-invalid @enderror"
               value="{{ old('harga') }}">
        @error('harga')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
        <label>Satuan</label>
        <input type="text" name="satuan" class="form-control" placeholder="contoh: kg, pcs"
               value="{{ old('satuan') }}">
    </div>
    <a href="{{ route('admin.services.index') }}" class="btn btn-secondary">Batal</a>
    <button type="submit" class="btn btn-primary">Simpan</button>
</form>
@endsection