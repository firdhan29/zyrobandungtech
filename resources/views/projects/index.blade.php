@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Semua Project</h1>
        <a href="{{ route('projects.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Tambah Project
        </a>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Project</th>
                                    <th>Client</th>
                                    <th>Total</th>
                                    <th>Progress</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($projects as $project)
                                <tr>
                                    <td>{{ $project->id }}</td>
                                    <td>
                                        <strong>{{ $project->name }}</strong>
                                        <br><small>{{ $project->description }}</small>
                                    </td>
                                    <td>
                                        {{ $project->client_name }}<br>
                                        <small>{{ $project->client_phone }}</small>
                                    </td>
                                    <td>
                                        Rp {{ number_format($project->total_amount, 0, ',', '.') }}<br>
                                        <small class="text-danger">Sisa: Rp {{ number_format($project->remaining_amount, 0, ',', '.') }}</small>
                                    </td>
                                    <td>
                                        <div class="progress" style="height: 25px;">
                                            <div class="progress-bar" style="width: {{ $project->progress_percentage }}%">
                                                {{ $project->progress_percentage }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $project->status == 'completed' ? 'success' : 'primary' }}">
                                            {{ ucfirst($project->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $project->start_date->format('d/m') }} - {{ $project->end_date->format('d/m') }}
                                    </td>
                                    <td>
                                        <a href="{{ route('projects.show', $project) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $projects->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection