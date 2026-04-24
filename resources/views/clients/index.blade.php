@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="h3 mb-4 text-gray-800">History Klien</h1>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        @forelse($clients as $client)
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card shadow h-100 border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="d-flex align-items-center">
                            <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px; font-weight: bold;">
                                {{ substr($client->name, 0, 2) }}
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold">{{ $client->name }}</h5>
                                <small class="text-muted">{{ $client->phone }}</small>
                            </div>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light rounded-circle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                <li>
                                    <button class="dropdown-item py-2" onclick="editClient({{ $client->id }}, '{{ $client->name }}', '{{ $client->phone }}', '{{ $client->email }}')">
                                        <i class="fas fa-edit me-2 text-primary"></i>Edit Data
                                    </button>
                                </li>
                                <li>
                                    <form action="{{ route('clients.destroy', $client) }}" method="POST" onsubmit="return confirm('Hapus data klien ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="dropdown-item py-2 text-danger">
                                            <i class="fas fa-trash me-2"></i>Hapus Klien
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="bg-light rounded p-3 mb-3">
                        <div class="row text-center">
                            <div class="col-6 border-end">
                                <div class="h5 mb-0 fw-bold text-primary">{{ $client->completed_projects_count }}</div>
                                <small class="text-muted">Project Selesai</small>
                            </div>
                            <div class="col-6">
                                <div class="h5 mb-0 fw-bold text-success">
                                    @php
                                        $totalRevenue = $client->projects->sum('total_amount');
                                    @endphp
                                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                                </div>
                                <small class="text-muted">Total Nilai</small>
                            </div>
                        </div>
                    </div>
                    
                    <a href="https://wa.me/{{ $client->phone }}" target="_blank" class="btn btn-success w-100 rounded-pill shadow-sm">
                        <i class="fab fa-whatsapp me-2"></i>Hubungi Kembali
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <i class="fas fa-users fa-4x text-muted mb-4"></i>
            <h4>Belum ada data klien</h4>
            <p class="text-muted">Klien akan muncul otomatis saat Anda membuat project baru</p>
        </div>
        @endforelse
    </div>
</div>

<!-- Edit Client Modal -->
<div class="modal fade" id="editClientModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Edit Data Klien</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editClientForm" method="POST">
                @csrf @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Klien</label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">No. WhatsApp</label>
                        <input type="text" name="phone" id="edit_phone" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" name="email" id="edit_email" class="form-control">
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function editClient(id, name, phone, email) {
    document.getElementById('editClientForm').action = '/clients/' + id;
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_phone').value = phone;
    document.getElementById('edit_email').value = email;
    new bootstrap.Modal(document.getElementById('editClientModal')).show();
}
</script>
@endpush
@endsection