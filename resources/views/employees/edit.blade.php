@extends('layouts.app')

@section('content')
<div class="card shadow-sm mx-auto" style="max-width: 600px;">
    <div class="card-header bg-white">
        <h4 class="mb-0">Edit Karyawan: {{ $employee->name }}</h4>
    </div>
    <div class="card-body">
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('employees.update', $employee->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Nama Karyawan</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $employee->name) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Posisi / Jabatan</label>
                <input type="text" name="position" class="form-control" value="{{ old('position', $employee->position) }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Kontak / No. HP</label>
                <input type="text" name="contact" class="form-control" value="{{ old('contact', $employee->contact) }}">
            </div>
            
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('employees.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-warning">Perbarui</button>
            </div>
        </form>
    </div>
</div>
@endsection
