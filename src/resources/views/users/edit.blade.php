@extends('layouts.app')

@section('title', 'Edit User')
@section('page-title', 'Edit User')

@section('content')
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-user-edit"></i> Edit User</h3>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
    <div class="card-body">
        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label">Nama</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            </div>

            @if(auth()->user()->isAdmin() && $user->id !== auth()->id())
            <div class="form-group">
                <label class="form-label">Role</label>
                <select name="role" id="role-select" class="form-select" required onchange="togglePerusahaanField()">
                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="teknisi" {{ old('role', $user->role) == 'teknisi' ? 'selected' : '' }}>Teknisi</option>
                    <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
                </select>
            </div>
            @else
                <input type="hidden" name="role" id="role-select" value="{{ $user->role }}">
            @endif

            <div class="form-group">
                <label class="form-label">No. Telepon (WhatsApp)</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" placeholder="Contoh: 08123456789">
            </div>
            
            <div class="form-group" id="perusahaan-field" style="display: none;">
                <label class="form-label">Perusahaan</label>
                <input type="text" name="perusahaan" class="form-control" value="{{ old('perusahaan', $user->perusahaan) }}" placeholder="Nama Perusahaan">
            </div>

            <script>
                function togglePerusahaanField() {
                    const roleSelect = document.getElementById('role-select');
                    const role = roleSelect ? roleSelect.value : '{{ $user->role }}';
                    const field = document.getElementById('perusahaan-field');
                    if (role === 'user') {
                        field.style.display = 'block';
                    } else {
                        field.style.display = 'none';
                    }
                }
                
                // Initial check
                document.addEventListener('DOMContentLoaded', togglePerusahaanField);
            </script>

            <div class="form-group">
                <label class="form-label">Password Baru (Kosongkan jika tidak ingin mengubah)</label>
                <div class="password-wrapper">
                    <input type="password" name="password" id="password" class="form-control">
                    <button type="button" class="password-toggle" onclick="togglePassword('password')">
                        <i class="fas fa-eye" id="password-icon"></i>
                    </button>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Konfirmasi Password Baru</label>
                <div class="password-wrapper">
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                    <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')">
                        <i class="fas fa-eye" id="password_confirmation-icon"></i>
                    </button>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

@endsection
