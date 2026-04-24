@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1">{{ $project->name }}</h1>
            <p class="mb-0 text-gray-600">{{ $project->description }}</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="https://wa.me/{{ $project->client_phone }}" target="_blank" class="btn btn-success rounded-pill px-3 shadow-sm">
                <i class="fab fa-whatsapp me-1"></i>Chat Client
            </a>
            <button class="btn btn-primary rounded-pill px-3 shadow-sm" onclick="updateProgress()">
                <i class="fas fa-sync-alt me-1"></i>Update Progress
            </button>
            <div class="dropdown">
                <button type="button" class="btn btn-outline-secondary dropdown-toggle rounded-pill px-3" data-bs-toggle="dropdown">
                    <i class="fas fa-cog me-1"></i>Lainnya
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                    <li><a class="dropdown-item py-2" href="{{ route('projects.edit', $project) }}">
                        <i class="fas fa-edit me-2 text-primary"></i>Edit Project
                    </a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('projects.destroy', $project) }}" method="POST" onsubmit="return confirm('Hapus project ini secara permanen?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="dropdown-item py-2 text-danger">
                                <i class="fas fa-trash me-2"></i>Hapus Project
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Project Info -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-white d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Detail Project</h6>
                    <span class="badge rounded-pill bg-{{ $project->status == 'completed' ? 'success' : 'primary' }}">
                        {{ ucfirst($project->status) }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2"><small class="text-muted d-block">Client</small> <strong>{{ $project->client_name }}</strong></p>
                            <p class="mb-2"><small class="text-muted d-block">Email</small> {{ $project->client_email }}</p>
                            <p class="mb-2"><small class="text-muted d-block">Phone</small> {{ $project->client_phone }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><small class="text-muted d-block">Total Biaya</small> <strong>Rp {{ number_format($project->total_amount, 0, ',', '.') }}</strong></p>
                            <p class="mb-2"><small class="text-muted d-block">DP</small> Rp {{ number_format($project->dp_amount, 0, ',', '.') }}</p>
                            <p class="mb-2"><small class="text-muted d-block">Sisa Bayar</small> <span class="text-danger fw-bold">Rp {{ number_format($project->remaining_amount, 0, ',', '.') }}</span></p>
                        </div>
                    </div>
                    
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Progress:</strong></p>
                            <div class="progress" style="height: 12px; border-radius: 6px;">
                                <div class="progress-bar bg-success" style="width: {{ $project->progress_percentage }}%">
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-1">
                                <small class="text-muted">{{ $project->progress_percentage }}% Selesai</small>
                                <small class="text-muted">{{ $project->progress_notes }}</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Jadwal:</strong></p>
                            <div class="bg-light p-2 rounded border small">
                                <i class="far fa-calendar-alt me-2 text-primary"></i>
                                {{ $project->start_date->format('d M Y') }} - {{ $project->end_date->format('d M Y') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payments -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-white d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Riwayat Pembayaran</h6>
                    <button class="btn btn-success btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#paymentModal">
                        <i class="fas fa-plus me-1"></i>Tambah Bayar
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Tipe</th>
                                    <th>Tanggal</th>
                                    <th>Jumlah</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($project->payments as $payment)
                                <tr>
                                    <td>{{ ucfirst($payment->payment_type) }}</td>
                                    <td>{{ $payment->payment_date->format('d M Y') }}</td>
                                    <td class="fw-bold text-success">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                    <td><span class="badge bg-{{ $payment->status_color }}">{{ ucfirst($payment->status) }}</span></td>
                                    <td>
                                        <form action="{{ route('payments.destroy', $payment) }}" method="POST" onsubmit="return confirm('Hapus riwayat bayar ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-link text-danger btn-sm p-0"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="text-center py-4 text-muted">Belum ada pembayaran</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Team -->
        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header py-3 bg-white d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Team Project</h6>
                    <button class="btn btn-outline-primary btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#teamModal">
                        <i class="fas fa-user-plus"></i>
                    </button>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($project->teams as $team)
                        <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar bg-light text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    {{ substr($team->name, 0, 2) }}
                                </div>
                                <div>
                                    <div class="fw-bold">{{ $team->name }}</div>
                                    <small class="text-muted">{{ $team->role }}</small>
                                </div>
                            </div>
                            <form action="{{ route('projects.removeTeam', [$project, $team]) }}" method="POST" onsubmit="return confirm('Hapus dari project?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-link text-danger btn-sm"><i class="fas fa-times"></i></button>
                            </form>
                        </li>
                        @empty
                        <li class="list-group-item text-center py-4 text-muted">Belum ada team</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Progress Modal -->
<div class="modal fade" id="progressModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title">Update Progress</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="progressForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-4">
                        <label class="form-label fw-bold">Progress (%)</label>
                        <input type="range" class="form-range" id="progress_percentage" name="progress_percentage" min="0" max="100" value="{{ $project->progress_percentage }}">
                        <div class="h3 text-center text-primary mt-2" id="progressValue">{{ $project->progress_percentage }}%</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Catatan Progress</label>
                        <textarea class="form-control" name="progress_notes" rows="3" placeholder="Apa yang sudah dikerjakan?">{{ $project->progress_notes }}</textarea>
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title">Tambah Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('payments.store') }}" method="POST">
                @csrf
                <input type="hidden" name="project_id" value="{{ $project->id }}">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tipe Pembayaran</label>
                        <select name="payment_type" class="form-select" required>
                            <option value="cicilan">Cicilan</option>
                            <option value="pelunasan">Pelunasan</option>
                            <option value="tambahan">Biaya Tambahan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Jumlah (Rp)</label>
                        <input type="text" name="amount" class="form-control currency-input" required 
                               value="{{ number_format($project->remaining_amount, 0, ',', '.') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tanggal</label>
                        <input type="date" name="payment_date" class="form-control" required value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Catatan</label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success rounded-pill px-4">Simpan Pembayaran</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Team Modal -->
<div class="modal fade" id="teamModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title">Tugaskan Team Member</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('projects.assignTeam', $project) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Pilih Member</label>
                        <select name="team_id" class="form-select" required>
                            <option value="">-- Pilih Member --</option>
                            @foreach($allTeams as $team)
                                @if(!$project->teams->contains($team->id))
                                    <option value="{{ $team->id }}">{{ $team->name }} ({{ $team->role }})</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Tugaskan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function updateProgress() {
    new bootstrap.Modal(document.getElementById('progressModal')).show();
}

document.getElementById('progress_percentage').addEventListener('input', function() {
    document.getElementById('progressValue').textContent = this.value + '%';
});

document.getElementById('progressForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    fetch('{{ route("projects.updateProgress", $project) }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            location.reload();
        }
    });
});
</script>
@endsection