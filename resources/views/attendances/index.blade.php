@extends('layouts.app')

@section('content')
<div class="card shadow-sm border-0 rounded-4 mb-4 bg-white">
    <div class="card-body p-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h4 class="fw-bold text-dark mb-1"><i class="bi bi-calendar-check text-primary me-2"></i> Absensi Karyawan</h4>
                <p class="text-muted small mb-0">Kelola absensi karyawan (Clock In / Clock Out)</p>
            </div>
            <div>
                <a href="{{ route('attendances.history') }}" class="btn btn-outline-dark rounded-pill fw-bold px-4 shadow-sm">
                    <i class="bi bi-clock-history me-1"></i> Riwayat Absensi
                </a>
            </div>
        </div>
        <form action="{{ route('attendances.index') }}" method="GET" class="d-flex align-items-center gap-2 bg-light p-2 rounded-3 border">
            <label for="date" class="form-label mb-0 fw-bold small text-muted px-2">Tanggal:</label>
            <input type="date" id="date" name="date" class="form-control border-0 bg-white shadow-sm" value="{{ $date }}" onchange="this.form.submit()">
        </form>
    </div>
</div>

@if ($errors->any())
    <div class="alert alert-danger border-0 rounded-3 shadow-sm">
        <ul class="mb-0 small">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Desktop Table View -->
<div class="card shadow-sm border-0 d-none d-lg-block mb-4">
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light text-muted">
                <tr>
                    <th class="ps-4">Nama Karyawan</th>
                    <th>Posisi</th>
                    <th class="text-center">Clock In</th>
                    <th class="text-center">Clock Out</th>
                    <th class="text-end pe-4">Aksi (Hari Ini)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $employee)
                    @php
                        $attendance = $attendances->get($employee->id);
                        $hasClockedIn = $attendance && $attendance->clock_in;
                        $hasClockedOut = $attendance && $attendance->clock_out;
                    @endphp
                <tr>
                    <td class="ps-4">
                        <div class="d-flex align-items-center">
                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3 text-secondary" style="width: 40px; height: 40px;">
                                <i class="bi bi-person-fill"></i>
                            </div>
                            <span class="fw-bold text-dark">{{ $employee->name }}</span>
                        </div>
                    </td>
                    <td><span class="text-muted small fw-medium">{{ $employee->position ?? 'Staff' }}</span></td>
                    <td class="text-center">
                        @if($hasClockedIn)
                            <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle px-3 py-2 rounded-pill"><i class="bi bi-box-arrow-in-right me-1"></i> {{ $attendance->clock_in }}</span>
                        @else
                            <span class="text-muted small">-</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($hasClockedOut)
                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger-subtle px-3 py-2 rounded-pill"><i class="bi bi-box-arrow-right me-1"></i> {{ $attendance->clock_out }}</span>
                        @else
                            <span class="text-muted small">-</span>
                        @endif
                    </td>
                    <td class="text-end pe-4">
                        <div class="d-flex justify-content-end gap-2">
                            <!-- Tombol Clock In -->
                            <form action="{{ route('attendances.clockIn') }}" method="POST">
                                @csrf
                                <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                                <input type="hidden" name="date" value="{{ $date }}">
                                <button type="submit" class="btn btn-sm btn-success rounded-3 px-3 fw-medium" {{ $hasClockedIn ? 'disabled' : '' }}>
                                    Clock In
                                </button>
                            </form>

                            <!-- Tombol Clock Out -->
                            <form action="{{ route('attendances.clockOut') }}" method="POST">
                                @csrf
                                <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                                <input type="hidden" name="date" value="{{ $date }}">
                                <button type="submit" class="btn btn-sm btn-danger rounded-3 px-3 fw-medium" {{ (!$hasClockedIn || $hasClockedOut) ? 'disabled' : '' }}>
                                    Clock Out
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-5">
                        <i class="bi bi-people fs-1 d-block mb-3"></i>
                        Belum ada karyawan. <a href="{{ route('employees.create') }}" class="text-decoration-none">Tambah karyawan sekarang</a>.
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
        @php
            $attendance = $attendances->get($employee->id);
            $hasClockedIn = $attendance && $attendance->clock_in;
            $hasClockedOut = $attendance && $attendance->clock_out;
        @endphp
        <div class="card shadow-sm border-0 mb-3 rounded-4 overflow-hidden">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3 border-bottom pb-3">
                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3 text-secondary" style="width: 50px; height: 50px;">
                        <i class="bi bi-person-fill fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1 text-dark">{{ $employee->name }}</h6>
                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary-subtle rounded-pill small">{{ $employee->position ?? 'Staff' }}</span>
                    </div>
                </div>
                
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <div class="bg-light p-2 rounded-3 text-center h-100 d-flex flex-column justify-content-center">
                            <small class="text-muted d-block mb-1">Clock In</small>
                            @if($hasClockedIn)
                                <span class="fw-bold text-success">{{ $attendance->clock_in }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-light p-2 rounded-3 text-center h-100 d-flex flex-column justify-content-center">
                            <small class="text-muted d-block mb-1">Clock Out</small>
                            @if($hasClockedOut)
                                <span class="fw-bold text-danger">{{ $attendance->clock_out }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="d-flex gap-2 pt-2">
                    <form action="{{ route('attendances.clockIn') }}" method="POST" class="w-50">
                        @csrf
                        <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                        <input type="hidden" name="date" value="{{ $date }}">
                        <button type="submit" class="btn btn-success w-100 rounded-3 fw-medium" {{ $hasClockedIn ? 'disabled' : '' }}>
                            <i class="bi bi-box-arrow-in-right me-1"></i> In
                        </button>
                    </form>
                    <form action="{{ route('attendances.clockOut') }}" method="POST" class="w-50">
                        @csrf
                        <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                        <input type="hidden" name="date" value="{{ $date }}">
                        <button type="submit" class="btn btn-danger w-100 rounded-3 fw-medium" {{ (!$hasClockedIn || $hasClockedOut) ? 'disabled' : '' }}>
                            <i class="bi bi-box-arrow-right me-1"></i> Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center text-muted py-5">
            <i class="bi bi-people fs-1 d-block mb-3"></i>
            <p>Belum ada karyawan. <a href="{{ route('employees.create') }}" class="text-decoration-none">Tambah karyawan sekarang</a>.</p>
        </div>
    @endforelse
</div>
@endsection
