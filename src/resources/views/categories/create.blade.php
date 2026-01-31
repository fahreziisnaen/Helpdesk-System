@extends('layouts.app')

@section('title', 'Tambah Kategori')
@section('page-title', 'Tambah Kategori')

@section('content')
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-tag"></i> Tambah Kategori</h3>
        <a href="{{ route('categories.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
    <div class="card-body">
        <form action="{{ route('categories.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label">Nama Kategori <span style="color: red;">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                @error('name')
                    <div style="color: var(--danger); font-size: 12px; margin-top: 5px;">
                        <i class="fas fa-exclamation-triangle"></i> {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                @error('description')
                    <div style="color: var(--danger); font-size: 12px; margin-top: 5px;">
                        <i class="fas fa-exclamation-triangle"></i> {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <div class="form-group" style="flex: 1;">
                    <label class="form-label">Urutan Tampil</label>
                    <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', 0) }}" min="0">
                    <small style="color: var(--gray);">Semakin kecil angka, semakin atas posisinya</small>
                </div>

                <div class="form-group" style="flex: 1;">
                    <label class="form-label">Status</label>
                    <div style="margin-top: 10px;">
                        <input type="hidden" name="is_active" value="0">
                        <label style="display: flex; align-items: center; cursor: pointer;">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} style="margin-right: 8px; width: 18px; height: 18px; cursor: pointer;">
                            <span>Aktif</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan
                </button>
                <a href="{{ route('categories.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
