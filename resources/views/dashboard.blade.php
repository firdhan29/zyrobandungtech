@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center">
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-tachometer-alt me-2 text-primary"></i>
                    Dashboard Zyro Bandung Tech
                </h1>
                <div class="ms-auto">
                    <span class="badge bg-success fs-6">{{ $ongoingProjects->count() }} Project Aktif</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-primary border-4 shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Project Selesai
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $completedProjects }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-success border-4 shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Revenue
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white">
                    <h6 class="m-0 font-weight-bold text-primary">Revenue 6 Bulan Terakhir</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area" style="height: 250px;">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-white">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-star me-2"></i>Top Klien
                    </h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @foreach($topClients as $client)
                        <div class="list-group-item d-flex align-items-center px-0 border-0 mb-2">
                            <div class="avatar bg-primary text-white rounded-circle me-3 d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px; font-size: 0.8rem;">
                                {{ strtoupper(substr($client->name, 0, 2)) }}
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold small">{{ $client->name }}</div>
                                <small class="text-muted">{{ $client->projects_count }} Project</small>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-light text-primary border">{{ $client->completed_projects_count }} Selesai</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-spinner fa-spin me-2"></i>Project Sedang Berjalan
                    </h6>
                    <a href="{{ route('projects.create') }}" class="btn btn-primary btn-sm rounded-pill">
                        <i class="fas fa-plus me-1"></i> Tambah Project
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Project</th>
                                    <th>Client</th>
                                    <th>Progress</th>
                                    <th>Pembayaran</th>
                                    <th>Deadline</th>
                                    <th>Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ongoingProjects as $project)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $project->name }}</div>
                                        <small class="text-muted">{{ Str::limit($project->description, 50) }}</small>
                                    </td>
                                    <td>
                                        <div>{{ $project->client_name }}</div>
                                        @if($project->client_phone)
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $project->client_phone) }}" target="_blank" 
                                           class="btn btn-outline-success btn-sm mt-1 py-0 px-2">
                                            <i class="fab fa-whatsapp"></i> Chat
                                        </a>
                                        @endif
                                    </td>
                                    <td style="width: 15%;">
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                                <div class="progress-bar bg-success" 
                                                     style="width: {{ $project->progress_percentage }}%">
                                                </div>
                                            </div>
                                            <small class="fw-bold">{{ $project->progress_percentage }}%</small>
                                        </div>
                                    </td>
                                    <td>
                                        <small class="d-block text-muted">Total: Rp{{ number_format($project->total_amount, 0, ',', '.') }}</small>
                                        <div class="text-danger fw-bold" style="font-size: 0.85rem;">
                                            Sisa: Rp{{ number_format($project->remaining_amount, 0, ',', '.') }}
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $daysLeft = now()->startOfDay()->diffInDays($project->end_date->startOfDay(), false);
                                        @endphp
                                        @if($daysLeft > 0)
                                            <span class="badge bg-warning text-dark">{{ $daysLeft }} Hari Lagi</span>
                                        @else
                                            <span class="badge bg-danger">Overdue</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill bg-{{ $project->status == 'completed' ? 'success' : 'primary' }}">
                                            {{ ucfirst($project->status) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('projects.show', $project) }}" class="btn btn-light btn-sm border">
                                            <i class="fas fa-eye text-info"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <i class="fas fa-folder-open fa-3x text-light mb-3 d-block"></i>
                                        <p class="text-muted">Belum ada project aktif saat ini.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const ctx = document.getElementById('revenueChart');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode(array_column($monthlyRevenue, 'month')) !!},
            datasets: [{
                label: 'Revenue (Rp)',
                data: {!! json_encode(array_column($monthlyRevenue, 'amount')) !!},
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37, 99, 235, 0.1)',
                tension: 0.3,
                fill: true,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString();
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
</script>
@endpush