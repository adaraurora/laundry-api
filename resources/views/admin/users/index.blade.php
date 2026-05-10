@extends('layouts.admin')
@section('title', 'Users')

@section('content')
<h2 class="mb-4">Daftar User</h2>
<table class="table table-bordered table-hover">
    <thead class="table-dark">
        <tr><th>#</th><th>Nama</th><th>Email</th><th>Role</th><th>Aksi</th></tr>
    </thead>
    <tbody>
        @foreach($users as $user)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td><span class="badge bg-{{ $user->role === 'admin' ? 'danger' : 'info' }}">{{ $user->role }}</span></td>
            <td>
                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button onclick="return confirm('Yakin hapus user ini?')" class="btn btn-sm btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $users->links() }}
@endsection