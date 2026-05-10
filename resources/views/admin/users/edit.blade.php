@extends('layouts.admin')
@section('title', 'Edit User')

@section('content')
<h2 class="mb-4">Edit User</h2>
<form action="{{ route('admin.users.update', $user) }}" method="POST">
    @csrf @method('PUT')
    <div class="mb-3">
        <label>Nama</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}">
    </div>
    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}">
    </div>
    <div class="mb-3">
        <label>Role</label>
        <select name="role" class="form-select">
            <option value="pelanggan" {{ $user->role === 'pelanggan' ? 'selected' : '' }}>Pelanggan</option>
            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
        </select>
    </div>
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Batal</a>
    <button type="submit" class="btn btn-primary">Update</button>
</form>
@endsection