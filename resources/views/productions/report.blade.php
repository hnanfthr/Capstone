@extends('layouts.app')

@section('content')

@push('styles')
<style>
    .product-category-header {
        background-color: #f8f9fa;
        position: sticky;
        top: 0;
        z-index: 10;
        border-bottom: 1px solid #dee2e6;
    }
    .product-item {
        cursor: pointer;
        transition: background-color 0.2s ease;
    }
    .product-item:hover {
        background-color: rgba(230, 92, 0, 0.08); /* admin-primary with opacity */
        color: #e65c00;
    }
</style>
@endpush

<div class="row g-4">
    <!-- Form Pencatatan Produksi -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow-sm border-0 rounded-4 h-100 bg-white">
            <div class="card-header bg-transparent border-0 pt-4 pb-0">
                <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-box-seam text-primary me-2"></i> Catat Barang Masuk</h5>
                <p class="text-muted small mt-1">Tambahkan stok kue yang baru diproduksi dari dapur.</p>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger border-0 rounded-3">
                        <ul class="mb-0 small">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('productions.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-medium">Kue yang Diproduksi</label>
                        <input type="hidden" name="product_id" id="selected_product_id" required>
                        <button type="button" class="btn btn-light border-0 w-100 text-start d-flex justify-content-between align-items-center rounded-3 py-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#productModal" id="btnSelectProduct">
                            <span class="text-muted"><i class="bi bi-box-seam me-2"></i>-- Klik untuk Pilih Kue --</span>
                            <i class="bi bi-chevron-down text-muted"></i>
                        </button>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label text-muted small fw-medium">Jumlah (Toples)</label>
                            <input type="number" name="quantity" class="form-control bg-light border-0" min="1" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="date" class="form-label text-muted small fw-medium">Tanggal Produksi</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="bi bi-calendar"></i></span>
                                <input type="text" id="date" name="production_date" class="form-control bg-light border-0 premium-date" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-muted small fw-medium">Catatan (Opsional)</label>
                        <textarea name="notes" class="form-control bg-light border-0" rows="2" placeholder="Contoh: Batch pagi"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 rounded-3 fw-bold py-2 shadow-sm">
                        <i class="bi bi-plus-circle me-1"></i> Simpan & Tambah Stok
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Laporan Produksi Berkala -->
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 rounded-4 bg-white h-100">
            <div class="card-header bg-transparent border-bottom pt-4 pb-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-journal-text text-success me-2"></i> Riwayat Produksi</h5>
                
                <!-- Filter Form -->
                <form action="{{ route('productions.report') }}" method="GET" class="d-flex flex-column flex-sm-row gap-2">
                    <div class="input-group input-group-sm shadow-sm rounded-3 overflow-hidden">
                        <span class="input-group-text bg-light border-0"><i class="bi bi-calendar"></i></span>
                        <input type="text" name="start_date" class="form-control border-0 bg-light premium-date" value="{{ request('start_date') }}" placeholder="Start Date">
                        <span class="input-group-text bg-light border-0">-</span>
                        <input type="text" name="end_date" class="form-control border-0 bg-light premium-date" value="{{ request('end_date') }}" placeholder="End Date">
                        <button type="submit" class="btn btn-dark px-3">Filter</button>
                    </div>
                    @if(request('start_date') || request('end_date'))
                        <a href="{{ route('productions.report') }}" class="btn btn-sm btn-outline-danger rounded-3 d-flex align-items-center justify-content-center px-3">Reset</a>
                    @endif
                </form>
            </div>
            
            <div class="card-body p-0">
                <!-- Desktop Table View -->
                <div class="table-responsive d-none d-lg-block">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-muted">
                            <tr>
                                <th class="ps-4">Tanggal</th>
                                <th>Produk</th>
                                <th class="text-center">Jumlah Masuk</th>
                                <th class="pe-4">Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($productions as $prod)
                            <tr>
                                <td class="ps-4"><span class="text-muted small fw-medium">{{ \Carbon\Carbon::parse($prod->production_date)->format('d M Y') }}</span></td>
                                <td class="fw-bold text-dark">{{ $prod->product ? $prod->product->nama : 'Produk Dihapus' }}</td>
                                <td class="text-center">
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle rounded-pill px-3">+{{ $prod->quantity }}</span>
                                </td>
                                <td class="pe-4 text-muted small">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span>{{ $prod->notes ?? '-' }}</span>
                                        <a href="{{ route('productions.print', $prod->id) }}" target="_blank" class="btn btn-sm btn-outline-dark rounded-circle" title="Cetak Label Batch">
                                            <i class="bi bi-printer"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-5">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i> Belum ada riwayat produksi di periode ini.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="d-block d-lg-none p-3">
                    @forelse($productions as $prod)
                        <div class="card shadow-sm border-0 mb-3 rounded-4 bg-light bg-opacity-50">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted small fw-medium"><i class="bi bi-calendar-event me-1"></i> {{ \Carbon\Carbon::parse($prod->production_date)->format('d M Y') }}</span>
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle rounded-pill px-3">+{{ $prod->quantity }} Toples</span>
                                </div>
                                <h6 class="fw-bold text-dark mb-2">{{ $prod->product ? $prod->product->nama : 'Produk Dihapus' }}</h6>
                                @if($prod->notes)
                                    <div class="bg-white p-2 rounded-3 small text-muted border mb-2">
                                        <i class="bi bi-info-circle me-1"></i> {{ $prod->notes }}
                                    </div>
                                @endif
                                <a href="{{ route('productions.print', $prod->id) }}" target="_blank" class="btn btn-sm btn-outline-dark w-100 rounded-3">
                                    <i class="bi bi-printer me-1"></i> Cetak Label Expired
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i> Belum ada riwayat produksi.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Pilih Kue -->
@push('modals')
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold" id="productModalLabel"><i class="bi bi-box-seam text-primary me-2"></i>Pilih Kue</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="input-group mb-3 shadow-sm rounded-3 overflow-hidden">
                    <span class="input-group-text bg-white border-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" class="form-control border-0 px-2" id="searchProduct" placeholder="Cari nama kue...">
                </div>
                
                <div class="list-group list-group-flush rounded-3 border" id="productListContainer">
                    @foreach($products as $kategori => $groupedProducts)
                        <div class="product-category-group">
                            <div class="list-group-item product-category-header text-muted fw-bold small text-uppercase py-2">
                                {{ $kategori }}
                            </div>
                            @foreach($groupedProducts as $product)
                                <button type="button" class="list-group-item list-group-item-action product-item d-flex align-items-center py-3 border-bottom" data-id="{{ $product->id }}" data-name="{{ $product->nama }}">
                                    <div class="bg-light rounded-circle d-flex justify-content-center align-items-center me-3" style="width: 40px; height: 40px;">
                                        <i class="bi bi-cake2 text-secondary"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-semibold text-dark">{{ $product->nama }}</h6>
                                        <small class="text-muted">Sisa Stok: {{ $product->stok }}</small>
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    @endforeach
                </div>
                <div id="noProductFound" class="text-center py-4 d-none">
                    <i class="bi bi-search fs-1 text-muted mb-2 d-block"></i>
                    <span class="text-muted">Kue tidak ditemukan.</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchProduct');
        const productItems = document.querySelectorAll('.product-item');
        const categoryGroups = document.querySelectorAll('.product-category-group');
        const noProductFound = document.getElementById('noProductFound');
        const btnSelectProduct = document.getElementById('btnSelectProduct');
        const hiddenProductId = document.getElementById('selected_product_id');

        // Search Functionality
        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            let totalVisibleItems = 0;

            categoryGroups.forEach(group => {
                let hasVisibleChild = false;
                const itemsInGroup = group.querySelectorAll('.product-item');

                itemsInGroup.forEach(item => {
                    const productName = item.getAttribute('data-name').toLowerCase();
                    if (productName.includes(searchTerm)) {
                        item.classList.remove('d-none');
                        item.classList.add('d-flex');
                        hasVisibleChild = true;
                        totalVisibleItems++;
                    } else {
                        item.classList.remove('d-flex');
                        item.classList.add('d-none');
                    }
                });

                // Hide category header if no items match
                const header = group.querySelector('.product-category-header');
                if (hasVisibleChild) {
                    header.style.display = 'block';
                } else {
                    header.style.display = 'none';
                }
            });

            // Show 'no results' message
            if (totalVisibleItems === 0) {
                noProductFound.classList.remove('d-none');
            } else {
                noProductFound.classList.add('d-none');
            }
        });

        // Select Product
        productItems.forEach(item => {
            item.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');

                // Update Form
                hiddenProductId.value = id;
                btnSelectProduct.innerHTML = `<span class="text-dark fw-semibold"><i class="bi bi-box-seam text-primary me-2"></i>${name}</span> <i class="bi bi-check-circle-fill text-success"></i>`;
                
                // Hide Modal
                const modalEl = document.getElementById('productModal');
                const modal = bootstrap.Modal.getInstance(modalEl);
                modal.hide();

                // Clear search
                searchInput.value = '';
                searchInput.dispatchEvent(new Event('input')); // Reset list
            });
        });
        
        // Form Validation to ensure product is selected
        const form = document.querySelector('form[action="{{ route('productions.store') }}"]');
        form.addEventListener('submit', function(e) {
            if (!hiddenProductId.value) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Silakan pilih kue terlebih dahulu!',
                });
            }
        });
    });
</script>
@endpush

@endsection
