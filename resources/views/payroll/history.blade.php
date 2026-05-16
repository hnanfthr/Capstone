@extends('layouts.app')

@section('content')
<div class="mb-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
    <div>
        <h3 class="fw-bold text-dark mb-1"><i class="bi bi-clock-history text-primary me-2"></i> Riwayat Penggajian</h3>
        <p class="text-muted small mb-0">Lihat histori penggajian karyawan dan unduh sebagai file Excel (CSV).</p>
    </div>
    <div>
        <a href="{{ route('payroll.index') }}" class="btn btn-outline-dark rounded-pill fw-bold px-4">
            <i class="bi bi-calculator me-1"></i> Kembali ke Kalkulator
        </a>
    </div>
</div>

<div class="card shadow-sm border-0 rounded-4 bg-white mb-4">
    <div class="card-header bg-transparent border-bottom pt-4 pb-3">
        <form action="{{ route('payroll.history') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-auto">
                <label class="form-label text-muted small fw-bold mb-0">Filter Tanggal:</label>
            </div>
            <div class="col-md-3">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light border-0"><i class="bi bi-calendar"></i></span>
                    <input type="text" name="start_date" class="form-control border-0 bg-light premium-date" value="{{ request('start_date') }}" placeholder="Start Date" required>
                </div>
            </div>
            <div class="col-md-auto text-center text-muted">s/d</div>
            <div class="col-md-3">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light border-0"><i class="bi bi-calendar"></i></span>
                    <input type="text" name="end_date" class="form-control border-0 bg-light premium-date" value="{{ request('end_date') }}" placeholder="End Date" required>
                </div>
            </div>
            <div class="col-md-auto">
                <button type="submit" class="btn btn-dark btn-sm rounded-3 px-3">Filter</button>
                @if(request('start_date') || request('end_date'))
                    <a href="{{ route('payroll.history') }}" class="btn btn-outline-danger btn-sm rounded-3 px-3 ms-1">Reset</a>
                @endif
            </div>
            
            <div class="col-md text-md-end mt-3 mt-md-0">
                <a href="{{ route('payroll.export', request()->all()) }}" class="btn btn-success btn-sm rounded-3 px-4 fw-bold">
                    <i class="bi bi-file-earmark-excel me-1"></i> Export Excel (CSV)
                </a>
            </div>
        </form>
    </div>
    
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-muted">
                    <tr>
                        <th class="ps-4">Tanggal</th>
                        <th>Nama Crew</th>
                        <th class="text-center">Jam Kerja</th>
                        <th class="text-center">Total Toples</th>
                        <th class="text-center">Rate / Toples</th>
                        <th class="text-end pe-4">Upah Diterima</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payrolls as $payroll)
                    <tr>
                        <td class="ps-4"><span class="text-muted small fw-medium">{{ \Carbon\Carbon::parse($payroll->date)->format('d M Y') }}</span></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2 text-secondary" style="width: 35px; height: 35px;">
                                    <i class="bi bi-person-fill"></i>
                                </div>
                                <span class="fw-bold text-dark">{{ $payroll->employee ? $payroll->employee->name : 'N/A' }}</span>
                            </div>
                        </td>
                        <td class="text-center"><span class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle px-3 rounded-pill">{{ number_format($payroll->hours_worked, 2) }} Jam</span></td>
                        <td class="text-center"><span class="text-dark">{{ $payroll->total_quantity }} Toples</span></td>
                        <td class="text-center"><span class="text-muted">Rp {{ number_format($payroll->rate_per_toples, 0, ',', '.') }}</span></td>
                        <td class="text-end pe-4">
                            <span class="text-success fw-bold">Rp {{ number_format($payroll->wage, 0, ',', '.') }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i> Belum ada riwayat penggajian pada periode ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($payrolls->hasPages())
    <div class="card-footer bg-transparent border-top p-3 d-flex justify-content-center">
        {{ $payrolls->links() }}
    </div>
    @endif
</div>
@endsection
