@extends('layouts.app')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <h3 class="fw-bold text-dark mb-0">Daftar Karyawan</h3>
    <a href="{{ route('employees.create') }}" class="btn btn-primary shadow-sm rounded-pill px-4">
        <i class="bi bi-person-plus me-1"></i> Tambah Karyawan
    </a>
</div>

<!-- Desktop Table View -->
<div class="card shadow-sm border-0 d-none d-lg-block mb-4">
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light text-muted">
                <tr>
                    <th class="ps-4">Nama</th>
                    <th>Posisi / Jabatan</th>
                    <th>Kontak / No. HP</th>
                    <th class="pe-4 text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $employee)
                <tr>
                    <td class="ps-4">
                        <div class="d-flex align-items-center">
                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3 text-secondary" style="width: 40px; height: 40px;">
                                <i class="bi bi-person-fill"></i>
                            </div>
                            <span class="fw-bold text-dark">{{ $employee->name }}</span>
                        </div>
                    </td>
                    <td><span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary-subtle rounded-pill">{{ $employee->position ?? 'Staff' }}</span></td>
                    <td>
                        @if($employee->contact)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $employee->contact) }}" target="_blank" class="text-decoration-none text-success small fw-medium">
                                <i class="bi bi-whatsapp"></i> {{ $employee->contact }}
                            </a>
                        @else
                            <span class="text-muted small">-</span>
                        @endif
                    </td>
                    <td class="pe-4 text-end">
                        <div class="btn-group shadow-sm rounded-3">
                            <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-sm btn-light border" title="Edit">
                                <i class="bi bi-pencil text-warning"></i>
                            </a>
                            <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data karyawan ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light border text-danger" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted py-5">
                        <i class="bi bi-people fs-1 d-block mb-2"></i> Belum ada data karyawan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Mobile Card View -->
<div class="d-block d-lg-none">
    @forelse($employees as $employee)
        <div class="card shadow-sm border-0 mb-3 rounded-4 overflow-hidden">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3 text-secondary" style="width: 50px; height: 50px;">
                        <i class="bi bi-person-fill fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1 text-dark">{{ $employee->name }}</h6>
                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary-subtle rounded-pill small">{{ $employee->position ?? 'Staff' }}</span>
                    </div>
                </div>
                
                @if($employee->contact)
                <div class="bg-light p-2 rounded-3 mb-3 d-flex align-items-center">
                    <i class="bi bi-telephone text-muted me-2"></i>
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $employee->contact) }}" target="_blank" class="text-decoration-none text-success small fw-medium">
                        {{ $employee->contact }}
                    </a>
                </div>
                @endif
                
                <div class="d-flex gap-2">
                    <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-warning w-50 rounded-3 text-dark fw-medium">
                        <i class="bi bi-pencil me-1"></i> Edit
                    </a>
                    <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" class="w-50" onsubmit="return confirm('Yakin ingin menghapus data karyawan ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100 rounded-3 fw-medium">
                            <i class="bi bi-trash me-1"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center text-muted py-5">
            <i class="bi bi-people fs-1 d-block mb-3"></i>
            <p>Belum ada data karyawan.</p>
        </div>
    @endforelse
</div>
@endsection
