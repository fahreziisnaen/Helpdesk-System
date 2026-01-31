@extends('layouts.app')

@section('title', 'Edit Tiket')
@section('page-title', 'Edit Tiket')

@section('content')
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-edit"></i> Edit Tiket</h3>
        <a href="{{ route('tickets.show', $ticket->id) }}" class="btn btn-secondary">Kembali</a>
    </div>
    <div class="card-body">
        <form action="{{ route('tickets.update', $ticket->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label">Judul Tiket</label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $ticket->title) }}" required>
            </div>

            <div class="d-flex gap-2">
                <div class="form-group" style="flex: 1;">
                    <label class="form-label">Kategori</label>
                    <select name="category_id" class="form-select" required>
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $ticket->category_id) == $category->id ? 'selected' : '' }}>
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
                    <label class="form-label">Prioritas</label>
                    <select name="priority" class="form-select" required>
                        <option value="low" {{ old('priority', $ticket->priority) == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ old('priority', $ticket->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ old('priority', $ticket->priority) == 'high' ? 'selected' : '' }}>High</option>
                        <option value="urgent" {{ old('priority', $ticket->priority) == 'urgent' ? 'selected' : '' }}>Urgent</option>
                    </select>
                </div>
            </div>

            @can('changeStatus', $ticket)
            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="open" {{ old('status', $ticket->status) == 'open' ? 'selected' : '' }}>Open</option>
                    <option value="in_progress" {{ old('status', $ticket->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="solved" {{ old('status', $ticket->status) == 'solved' ? 'selected' : '' }}>Solved</option>
                    <option value="closed" {{ old('status', $ticket->status) == 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </div>
            @endcan

            @can('assign', $ticket)
            <div class="form-group">
                <label class="form-label">Ditugaskan Ke</label>
                <select name="assigned_to" class="form-select">
                    <option value="">Belum ditugaskan</option>
                    @foreach($teknisi as $tech)
                    <option value="{{ $tech->id }}" {{ old('assigned_to', $ticket->assigned_to) == $tech->id ? 'selected' : '' }}>
                        {{ $tech->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            @endcan

            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control" rows="6" required>{{ old('description', $ticket->description) }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Lampiran</label>
                @if($ticket->attachment)
                <div class="mb-2">
                    <a href="{{ asset('storage/' . $ticket->attachment) }}" target="_blank">{{ basename($ticket->attachment) }}</a>
                </div>
                @endif
                <input type="file" name="attachment" class="form-control" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                <small style="color: var(--gray);">Biarkan kosong untuk tidak mengubah lampiran</small>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
                <a href="{{ route('tickets.show', $ticket->id) }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
