@extends('layouts.app')

@section('title', 'Buat Tiket Baru')
@section('page-title', 'Buat Tiket Baru')

@section('content')
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-plus-circle"></i> Buat Tiket Baru</h3>
        <a href="{{ route('tickets.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
    <div class="card-body">
        <form action="{{ route('tickets.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label class="form-label">Judul Tiket <span style="color: red;">*</span></label>
                <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
            </div>

            <div class="d-flex gap-2">
                <div class="form-group" style="flex: 1;">
                    <label class="form-label">Kategori <span style="color: red;">*</span></label>
                    <select name="category_id" class="form-select" required>
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div style="color: var(--danger); font-size: 12px; margin-top: 5px;">
                            <i class="fas fa-exclamation-triangle"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group" style="flex: 1;">
                    <label class="form-label">Prioritas <span style="color: red;">*</span></label>
                    <select name="priority" class="form-select" required>
                        <option value="">Pilih Prioritas</option>
                        <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                        <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Deskripsi <span style="color: red;">*</span></label>
                <textarea name="description" class="form-control" rows="6" required>{{ old('description') }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Lampiran</label>
                <input type="file" name="attachment" class="form-control" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                <small style="color: var(--gray);">Format: PDF, DOC, DOCX, JPG, PNG (Max: 10MB)</small>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Tiket
                </button>
                <a href="{{ route('tickets.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
