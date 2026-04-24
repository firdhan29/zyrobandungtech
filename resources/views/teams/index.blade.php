@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manajemen Team</h1>
    </div>

    <!-- Add Team Member -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Tambah Team Member</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('teams.store') }}" method="POST" class="row g-3">
                @csrf
                <div class="col-md-3">
                    <input type="text" class="form-control" name="name" placeholder="Nama Lengkap" required>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control" name="role" placeholder="Posisi/Jabatan" required>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control" name="phone" placeholder="No. HP">
                </div>
                <div class="col-md-3">
                    <input type="email" class="form-control" name="email" placeholder="Email">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Tambah Member
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Team List -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Team ({{ $teams->count() }})</h6>
        </div>
        <div class="card-body">
            @forelse($teams as $team)
            <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                <div class="d-flex align-items-center">
                    <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                        {{ substr($team->name, 0, 2) }}
                    </div>
                    <div>
                        <h6>{{ $team->name }}</h6>
                        <small class="text-muted">{{ $team->role }}</small>
                        @if($team->phone)
                            <br><small><i class="fas fa-phone me-1"></i>{{ $team->phone }}</small>
                        @endif
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <span class="badge bg-{{ $team->is_active ? 'success' : 'secondary' }} me-2">
                        {{ $team->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                    <form action="{{ route('teams.destroy', $team) }}" method="POST" onsubmit="return confirm('Hapus team member ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-link text-danger btn-sm p-0">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="text-center py-5">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <p class="text-muted">Belum ada team member</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection