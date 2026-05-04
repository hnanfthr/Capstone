@extends('layouts.app')

@section('content')
<div class="card shadow-sm mb-4">
    <div class="card-body d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Absensi Karyawan</h4>
            <p class="text-muted mb-0">Kelola absensi karyawan (Clock In / Clock Out)</p>
        </div>
        <form action="{{ route('attendances.index') }}" method="GET" class="d-flex align-items-center gap-2">
            <label for="date" class="form-label mb-0 fw-bold">Tanggal:</label>
            <input type="date" id="date" name="date" class="form-control" value="{{ $date }}" onchange="this.form.submit()">
        </form>
    </div>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Nama Karyawan</th>
                    <th>Posisi</th>
                    <th class="text-center">Clock In</th>
                    <th class="text-center">Clock Out</th>
                    <th class="text-center">Aksi (Hari Ini)</th>
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
                    <td class="align-middle fw-bold">{{ $employee->name }}</td>
                    <td class="align-middle">{{ $employee->position ?? '-' }}</td>
                    <td class="text-center align-middle">
                        @if($hasClockedIn)
                            <span class="badge bg-success fs-6">{{ $attendance->clock_in }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td class="text-center align-middle">
                        @if($hasClockedOut)
                            <span class="badge bg-secondary fs-6">{{ $attendance->clock_out }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td class="text-center align-middle">
                        <div class="d-flex justify-content-center gap-2">
                            <!-- Tombol Clock In -->
                            <form action="{{ route('attendances.clockIn') }}" method="POST">
                                @csrf
                                <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                                <input type="hidden" name="date" value="{{ $date }}">
                                <button type="submit" class="btn btn-sm btn-success" {{ $hasClockedIn ? 'disabled' : '' }}>
                                    Clock In
                                </button>
                            </form>

                            <!-- Tombol Clock Out -->
                            <form action="{{ route('attendances.clockOut') }}" method="POST">
                                @csrf
                                <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                                <input type="hidden" name="date" value="{{ $date }}">
                                <button type="submit" class="btn btn-sm btn-danger" {{ (!$hasClockedIn || $hasClockedOut) ? 'disabled' : '' }}>
                                    Clock Out
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4">Belum ada karyawan. <a href="{{ route('employees.create') }}">Tambah karyawan sekarang</a>.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
