@extends('layouts.admin')
@section('title', 'Services')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h2>Daftar Layanan</h2>
    <a href="{{ route('admin.services.create') }}" class="btn btn-primary">+ Tambah</a>
</div>

<table class="table table-bordered table-hover">
    <thead class="table-dark">
        <tr><th>#</th><th>Nama Layanan</th><th>Deskripsi</th><th>Harga</th><th>Satuan</th><th>Aksi</th></tr>
    </thead>
    <tbody>
        @foreach($services as $service)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $service->nama_layanan }}</td>
            <td>{{ $service->deskripsi ?? '-' }}</td>
            <td>Rp {{ number_format($service->harga, 0, ',', '.') }}</td>
            <td>{{ $service->satuan }}</td>
            <td>
                <a href="{{ route('admin.services.edit', $service) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('admin.services.destroy', $service) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button onclick="return confirm('Yakin hapus?')" class="btn btn-sm btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $services->links() }}
@endsection