@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('projects.show', $project) }}" class="btn btn-secondary me-3">
            <i class="fas fa-arrow-left me-1"></i>Kembali
        </a>
        <h1 class="h3 mb-0">Edit Project: {{ $project->name }}</h1>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-body p-5">
                    <form action="{{ route('projects.update', $project) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold">Nama Project <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       name="name" value="{{ old('name', $project->name) }}" required>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold">Status</label>
                                <select name="status" class="form-select">
                                    <option value="planning" {{ $project->status == 'planning' ? 'selected' : '' }}>Planning</option>
                                    <option value="development" {{ $project->status == 'development' ? 'selected' : '' }}>Development</option>
                                    <option value="testing" {{ $project->status == 'testing' ? 'selected' : '' }}>Testing</option>
                                    <option value="completed" {{ $project->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="on_hold" {{ $project->status == 'on_hold' ? 'selected' : '' }}>On Hold</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Deskripsi</label>
                            <textarea class="form-control" name="description" rows="3">{{ old('description', $project->description) }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-4">
                                <label class="form-label fw-bold">Nama Client <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="client_name" value="{{ old('client_name', $project->client_name) }}" required>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label class="form-label fw-bold">No. WhatsApp <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="client_phone" value="{{ old('client_phone', $project->client_phone) }}" required>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label class="form-label fw-bold">Email Client</label>
                                <input type="email" class="form-control" name="client_email" value="{{ old('client_email', $project->client_email) }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-4">
                                <label class="form-label fw-bold">Total Biaya <span class="text-danger">*</span></label>
                                <input type="text" class="form-control currency-input" name="total_amount" 
                                       value="{{ number_format(old('total_amount', $project->total_amount), 0, ',', '.') }}" required>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label class="form-label fw-bold">Tanggal Mulai <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="start_date" value="{{ old('start_date', $project->start_date->format('Y-m-d')) }}" required>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label class="form-label fw-bold">Tanggal Selesai <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="end_date" value="{{ old('end_date', $project->end_date->format('Y-m-d')) }}" required>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <button type="submit" class="btn btn-primary px-5 rounded-pill">
                                <i class="fas fa-save me-2"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
