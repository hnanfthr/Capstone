@extends('layouts.app')

@section('content')
<div class="mb-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
    <div>
        <h3 class="fw-bold text-dark mb-1"><i class="bi bi-cash-stack text-primary me-2"></i> Perhitungan Gaji</h3>
        <p class="text-muted small mb-0">Hitung upah harian secara proporsional berdasarkan total jam kerja.</p>
    </div>
    <div>
        <a href="{{ route('payroll.history') }}" class="btn btn-outline-dark rounded-pill fw-bold px-4">
            <i class="bi bi-clock-history me-1"></i> Riwayat Penggajian
        </a>
    </div>
</div>

<!-- Form Perhitungan -->
<div class="card shadow-sm border-0 rounded-4 mb-4 bg-white">
    <div class="card-body p-4">
        <form action="{{ route('payroll.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label for="date" class="form-label fw-bold small text-muted">Tanggal</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-0"><i class="bi bi-calendar"></i></span>
                    <input type="text" id="date" name="date" class="form-control bg-light border-0 premium-date" value="{{ $date }}">
                </div>
            </div>
            <div class="col-md-4">
                <label for="quantity" class="form-label fw-bold small text-muted">Kuantitas Toples (Total)</label>
                <input type="number" id="quantity" name="quantity" class="form-control bg-light border-0" value="{{ $quantity }}" required>
                <div class="form-text small"><i class="bi bi-info-circle me-1"></i>Diisi otomatis dari dapur.</div>
            </div>
            <div class="col-md-3">
                <label for="rate" class="form-label fw-bold small text-muted">Rate / Toples (Rp)</label>
                <input type="number" id="rate" name="rate" class="form-control bg-light border-0" value="{{ $rate }}" required>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100 rounded-3 fw-bold shadow-sm">
                    <i class="bi bi-calculator"></i> Hitung
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Ringkasan -->
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
            <div class="card-body d-flex align-items-center">
                <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3 text-primary d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                    <i class="bi bi-clock-history fs-3"></i>
                </div>
                <div>
                    <p class="text-muted small fw-medium mb-1">Total Jam Kerja Crew</p>
                    <h3 class="fw-bold mb-0 text-dark">{{ number_format($totalHours, 2) }} <span class="fs-6 text-muted">Jam</span></h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
            <div class="card-body d-flex align-items-center">
                <div class="bg-success bg-opacity-10 p-3 rounded-circle me-3 text-success d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                    <i class="bi bi-wallet2 fs-3"></i>
                </div>
                <div>
                    <p class="text-muted small fw-medium mb-1">Total Upah (Qty x Rate)</p>
                    <h3 class="fw-bold mb-0 text-success">Rp {{ number_format($totalWage, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Daftar Penggajian -->
<div class="card shadow-sm border-0 mb-4 d-none d-lg-block">
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light text-muted">
                <tr>
                    <th class="ps-4">Nama Crew</th>
                    <th class="text-center">Jam Masuk</th>
                    <th class="text-center">Jam Keluar</th>
                    <th class="text-center">Total Jam (Desimal)</th>
                    <th class="text-end pe-4">Upah Diterima</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payrollData as $data)
                <tr>
                    <td class="ps-4">
                        <div class="d-flex align-items-center">
                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3 text-secondary" style="width: 40px; height: 40px;">
                                <i class="bi bi-person-fill"></i>
                            </div>
                            <span class="fw-bold text-dark">{{ $data['employee'] }}</span>
                        </div>
                    </td>
                    <td class="text-center"><span class="badge bg-light text-dark border">{{ \Carbon\Carbon::parse($data['clock_in'])->format('H:i') }}</span></td>
                    <td class="text-center"><span class="badge bg-light text-dark border">{{ \Carbon\Carbon::parse($data['clock_out'])->format('H:i') }}</span></td>
                    <td class="text-center"><span class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle px-3 rounded-pill">{{ number_format($data['hours_worked'], 2) }} Jam</span></td>
                    <td class="text-end pe-4">
                        <span class="text-success fw-bold fs-6">Rp {{ number_format($data['wage'], 0, ',', '.') }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-5">
                        <i class="bi bi-clock-history fs-1 d-block mb-2"></i> Belum ada karyawan yang Clock In dan Clock Out pada tanggal ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Mobile Card View -->
<div class="d-block d-lg-none">
    @forelse($payrollData as $data)
        <div class="card shadow-sm border-0 mb-3 rounded-4 overflow-hidden">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3 border-bottom pb-3">
                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3 text-secondary" style="width: 50px; height: 50px;">
                        <i class="bi bi-person-fill fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 text-dark">{{ $data['employee'] }}</h6>
                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle rounded-pill small mt-1">Crew</span>
                    </div>
                </div>
                
                <div class="row g-2 mb-3">
                    <div class="col-4">
                        <div class="bg-light p-2 rounded-3 text-center h-100">
                            <small class="text-muted d-block mb-1">Masuk</small>
                            <span class="fw-bold text-dark small">{{ \Carbon\Carbon::parse($data['clock_in'])->format('H:i') }}</span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="bg-light p-2 rounded-3 text-center h-100">
                            <small class="text-muted d-block mb-1">Keluar</small>
                            <span class="fw-bold text-dark small">{{ \Carbon\Carbon::parse($data['clock_out'])->format('H:i') }}</span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="bg-light p-2 rounded-3 text-center h-100 border border-primary-subtle">
                            <small class="text-primary d-block mb-1">Durasi</small>
                            <span class="fw-bold text-primary small">{{ number_format($data['hours_worked'], 1) }} Jam</span>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between align-items-center bg-success bg-opacity-10 p-3 rounded-3 border border-success-subtle mt-2">
                    <span class="text-success fw-medium small">Upah Diterima:</span>
                    <span class="fw-bold text-success fs-5">Rp {{ number_format($data['wage'], 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center text-muted py-5">
            <i class="bi bi-clock-history fs-1 d-block mb-3"></i>
            <p>Belum ada absensi pada tanggal ini.</p>
        </div>
    @endforelse
</div>

@if(count($payrollData) > 0)
<div class="card border-0 shadow-sm rounded-4 mt-4 bg-white">
    <div class="card-body p-4 text-center">
        <h5 class="fw-bold mb-3">Simpan Data Penggajian</h5>
        <p class="text-muted small mb-4">Pastikan hasil hitungan di atas sudah benar sebelum menyimpan ke riwayat.</p>
        <form action="{{ route('payroll.store') }}" method="POST">
            @csrf
            <input type="hidden" name="date" value="{{ $date }}">
            <input type="hidden" name="rate" value="{{ $rate }}">
            <input type="hidden" name="quantity" value="{{ $quantity }}">
            
            @foreach($attendances as $index => $att)
                <input type="hidden" name="employees[]" value="{{ $att->employee->id }}">
                <input type="hidden" name="hours[]" value="{{ $payrollData[$index]['hours_worked'] }}">
                <input type="hidden" name="wages[]" value="{{ $payrollData[$index]['wage'] }}">
            @endforeach
            
            <button type="submit" class="btn btn-success btn-lg rounded-pill px-5 fw-bold shadow-sm">
                <i class="bi bi-save2 me-2"></i> Simpan ke Riwayat
            </button>
        </form>
    </div>
</div>
@endif

@endsection
