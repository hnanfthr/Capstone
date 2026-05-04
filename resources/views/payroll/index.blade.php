@extends('layouts.app')

@section('content')
<div class="card shadow-sm mb-4">
    <div class="card-header bg-dark text-white">
        <h5 class="mb-0">Perhitungan Penggajian Karyawan</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('payroll.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label for="date" class="form-label fw-bold">Tanggal</label>
                <input type="date" id="date" name="date" class="form-control" value="{{ $date }}">
            </div>
            <div class="col-md-3">
                <label for="quantity" class="form-label fw-bold">Kuantitas Toples (Total)</label>
                <input type="number" id="quantity" name="quantity" class="form-control" value="{{ $quantity }}" required>
                <small class="text-muted">Diisi otomatis dari data produksi hari ini.</small>
            </div>
            <div class="col-md-3">
                <label for="rate" class="form-label fw-bold">Rate / Toples (Rp)</label>
                <input type="number" id="rate" name="rate" class="form-control" value="{{ $rate }}" required>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">Hitung Upah</button>
            </div>
        </form>
    </div>
</div>

<!-- Ringkasan Upah -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card border-primary">
            <div class="card-body text-center">
                <h6 class="card-subtitle mb-2 text-muted">Total Jam Kerja (Semua Crew)</h6>
                <h3 class="card-title text-primary">{{ number_format($totalHours, 2) }} Jam</h3>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-success">
            <div class="card-body text-center">
                <h6 class="card-subtitle mb-2 text-muted">Total Upah Hari Ini (Kuantitas x Rate)</h6>
                <h3 class="card-title text-success">Rp {{ number_format($totalWage, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover table-bordered mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nama Crew</th>
                    <th class="text-center">Jam Masuk</th>
                    <th class="text-center">Jam Keluar</th>
                    <th class="text-center">Total Jam (Desimal)</th>
                    <th class="text-end">Upah / Crew</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payrollData as $data)
                <tr>
                    <td class="fw-bold">{{ $data['employee'] }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($data['clock_in'])->format('H:i') }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($data['clock_out'])->format('H:i') }}</td>
                    <td class="text-center">{{ number_format($data['hours_worked'], 2) }} Jam</td>
                    <td class="text-end text-success fw-bold">Rp {{ number_format($data['wage'], 2, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">
                        Belum ada karyawan yang Clock In dan Clock Out pada tanggal ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
