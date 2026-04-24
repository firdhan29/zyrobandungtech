@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('projects.index') }}" class="btn btn-secondary me-3">
            <i class="fas fa-arrow-left me-1"></i>Kembali
        </a>
        <h1 class="h3 mb-0">Tambah Project Baru</h1>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-body p-5">
                    <form action="{{ route('projects.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold">Nama Project <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       name="name" value="{{ old('name') }}" required>
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold">Deskripsi</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          name="description" rows="3">{{ old('description') }}</textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <label class="form-label fw-bold">Pilih Client <span class="text-danger">*</span></label>
                                <select name="client_id" id="client_id" class="form-select @error('client_id') is-invalid @enderror">
                                    <option value="">-- Tambah Client Baru --</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                            {{ $client->name }} ({{ $client->phone }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div id="new_client_fields">
                            <div class="row">
                                <div class="col-md-4 mb-4">
                                    <label class="form-label fw-bold">Nama Client <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('client_name') is-invalid @enderror" 
                                           name="client_name" value="{{ old('client_name') }}">
                                </div>
                                <div class="col-md-4 mb-4">
                                    <label class="form-label fw-bold">No. WhatsApp <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('client_phone') is-invalid @enderror" 
                                           name="client_phone" value="{{ old('client_phone') }}" placeholder="08123456789">
                                </div>
                                <div class="col-md-4 mb-4">
                                    <label class="form-label fw-bold">Email Client</label>
                                    <input type="email" class="form-control" name="client_email" value="{{ old('client_email') }}">
                                </div>
                            </div>
                        </div>

                        <script>
                            document.getElementById('client_id').addEventListener('change', function() {
                                const newClientFields = document.getElementById('new_client_fields');
                                const inputs = newClientFields.querySelectorAll('input');
                                if (this.value) {
                                    newClientFields.style.display = 'none';
                                    inputs.forEach(input => input.required = false);
                                } else {
                                    newClientFields.style.display = 'block';
                                    inputs.forEach(input => {
                                        if (input.name !== 'client_email') input.required = true;
                                    });
                                }
                            });
                            // Trigger once on load
                            document.getElementById('client_id').dispatchEvent(new Event('change'));
                        </script>

                        <div class="row">
                            <div class="col-md-4 mb-4">
                                <label class="form-label fw-bold">Total Biaya <span class="text-danger">*</span></label>
                                <input type="text" class="form-control currency-input @error('total_amount') is-invalid @enderror" 
                                       name="total_amount" value="{{ old('total_amount') ? number_format(old('total_amount'), 0, ',', '.') : '' }}" required>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label class="form-label fw-bold">DP <span class="text-danger">*</span></label>
                                <input type="text" class="form-control currency-input @error('dp_amount') is-invalid @enderror" 
                                       name="dp_amount" value="{{ old('dp_amount') ? number_format(old('dp_amount'), 0, ',', '.') : '' }}" required>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label class="form-label fw-bold">Tanggal Mulai <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                       name="start_date" value="{{ old('start_date') }}" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold">Tanggal Selesai <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                                       name="end_date" value="{{ old('end_date') }}" required>
                                @error('end_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <a href="{{ route('projects.index') }}" class="btn btn-secondary me-md-2">Batal</a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save me-2"></i>Simpan Project
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection