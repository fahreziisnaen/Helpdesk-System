@extends('layouts.app')

@section('title', 'Laporan Tiket')
@section('page-title', 'Laporan Tiket')

@section('content')
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-chart-bar"></i> Generate Laporan</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('reports.generate') }}" method="POST">
            @csrf

            <div class="d-flex gap-2 mb-3">
                <div class="form-group" style="flex: 1;">
                    <label class="form-label">Tanggal Mulai</label>
                    <input type="date" name="start_date" class="form-control" value="{{ old('start_date', date('Y-m-01')) }}" required>
                </div>

                <div class="form-group" style="flex: 1;">
                    <label class="form-label">Tanggal Akhir</label>
                    <input type="date" name="end_date" class="form-control" value="{{ old('end_date', date('Y-m-d')) }}" required>
                </div>
            </div>

            <div class="d-flex gap-2 mb-3">
                <div class="form-group" style="flex: 1;">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="open" {{ old('status') == 'open' ? 'selected' : '' }}>Open</option>
                        <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="solved" {{ old('status') == 'solved' ? 'selected' : '' }}>Solved</option>
                        <option value="closed" {{ old('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>

                <div class="form-group" style="flex: 1;">
                    <label class="form-label">Teknisi</label>
                    <select name="teknisi_id" class="form-select">
                        <option value="">Semua Teknisi</option>
                        @foreach($teknisi as $tech)
                        <option value="{{ $tech->id }}" {{ old('teknisi_id') == $tech->id ? 'selected' : '' }}>
                            {{ $tech->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Format Export</label>
                <select name="format" class="form-select" required>
                    <option value="excel" {{ old('format') == 'excel' ? 'selected' : '' }}>Excel (.xlsx)</option>
                    <option value="pdf" {{ old('format') == 'pdf' ? 'selected' : '' }}>PDF</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-download"></i> Generate Laporan
            </button>
        </form>
    </div>
</div>
@endsection
