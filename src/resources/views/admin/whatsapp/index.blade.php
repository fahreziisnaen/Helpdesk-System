@extends('layouts.app')

@section('title', 'Konfigurasi WhatsApp')
@section('page-title', 'Konfigurasi WhatsApp')

@section('content')
<div class="card">
    <div class="card-header">
        <h3><i class="fab fa-whatsapp"></i> WhatsApp Gateway</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h4>Status Koneksi</h4>
                <div class="mt-3">
                    @if(isset($status['status']) && $status['status'] === 'connected')
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> Terhubung ke WhatsApp
                        </div>
                        <form action="{{ route('admin.whatsapp.reset') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mereset koneksi? Anda harus scan QR ulang.')">
                            @csrf
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-sync"></i> Reset WhatsApp (Ganti Nomor)
                            </button>
                        </form>
                    @elseif(isset($status['qr']))
                        <div class="alert alert-warning">
                            <i class="fas fa-qrcode"></i> Scan QR Code di bawah untuk menghubungkan
                        </div>
                        <div class="text-center p-4 border rounded bg-light">
                            <img src="{{ $status['qr'] }}" alt="QR Code" style="max-width: 300px;">
                            <p class="mt-2 text-muted">QR akan terupdate otomatis jika koneksi gagal.</p>
                        </div>
                        <div class="mt-3">
                             <button class="btn btn-primary" onclick="window.location.reload()">
                                <i class="fas fa-sync"></i> Cek Status Koneksi
                             </button>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-spinner fa-spin"></i> Menghubungkan ke Gateway...
                        </div>
                        <button class="btn btn-primary" onclick="window.location.reload()">
                            <i class="fas fa-sync"></i> Refresh Halaman
                        </button>
                    @endif
                </div>
            </div>

            <div class="col-md-6">
                <h4>Konfigurasi Group</h4>
                <div class="mt-3">
                    @if(isset($status['status']) && $status['status'] === 'connected')
                        <form action="{{ route('admin.whatsapp.store') }}" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="group_id">Pilih Group Tujuan Notifikasi</label>
                                <select name="group_id" id="group_id" class="form-control mt-1">
                                    <option value="">-- Pilih Group --</option>
                                    @foreach($groups as $group)
                                        <option value="{{ $group['id'] }}" {{ $selectedGroupId == $group['id'] ? 'selected' : '' }}>
                                            {{ $group['subject'] }} ({{ $group['id'] }})
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Nomor WhatsApp harus sudah bergabung di dalam grup ini.</small>
                            </div>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Simpan Konfigurasi Group
                            </button>
                        </form>
                        
                        <div class="mt-4 p-3 border rounded">
                            <h5>Petunjuk:</h5>
                            <ol class="mt-2">
                                <li>Pastikan nomor WA sudah masuk ke grup tujuan.</li>
                                <li>Refresh halaman ini jika grup baru belum muncul.</li>
                                <li>Pilih grup dan Simpan.</li>
                            </ol>
                        </div>
                    @else
                        <div class="alert alert-secondary">
                            Silakan hubungkan WhatsApp terlebih dahulu untuk melihat daftar grup.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Poll status every 10 seconds if not connected and QR is visible
    @if(isset($status['qr']) || (isset($status['status']) && $status['status'] !== 'connected'))
    setInterval(function() {
        $.ajax({
            url: '{{ route("admin.whatsapp.index") }}',
            method: 'GET',
            success: function(data) {
                // If status changed to connected in background, reload
                if ($(data).find('.alert-success').length > 0) {
                    window.location.reload();
                }
            }
        });
    }, 10000);
    @endif
</script>
@endpush
@endsection
