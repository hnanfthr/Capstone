@extends('layouts.app')

@section('content')
<div class="mb-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
    <div>
        <h3 class="fw-bold text-dark mb-1"><i class="bi bi-journal-plus text-primary me-2"></i> Buat Pesanan Manual</h3>
        <p class="text-muted small mb-0">Catat pesanan dari WhatsApp atau sumber lain secara manual ke dalam sistem.</p>
    </div>
    <div>
        <a href="{{ route('orders.index') }}" class="btn btn-outline-dark rounded-pill fw-bold px-4">
            <i class="bi bi-arrow-left me-1"></i> Batal
        </a>
    </div>
</div>

<form action="{{ route('orders.store') }}" method="POST">
    @csrf
    <div class="row g-4">
        <!-- Data Pelanggan -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 bg-white h-100">
                <div class="card-header bg-transparent border-bottom pt-4 pb-3">
                    <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-person-lines-fill text-success me-2"></i> Data Pelanggan</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Nama Pemesan <span class="text-danger">*</span></label>
                        <input type="text" name="customer_name" class="form-control bg-light border-0" required placeholder="Contoh: Budi Susanto">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Nomor WhatsApp</label>
                        <input type="text" name="whatsapp_number" class="form-control bg-light border-0" placeholder="Contoh: 08123456789">
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-muted small fw-bold">Alamat Pengiriman / Catatan</label>
                        <textarea name="address" class="form-control bg-light border-0" rows="3" placeholder="Alamat lengkap atau catatan tambahan..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Status Pesanan</label>
                        <select name="status" class="form-select bg-light border-0">
                            <option value="Pending" selected>Pending</option>
                            <option value="Sudah Diambil">Sudah Diambil</option>
                            <option value="Dikirim">Dikirim</option>
                            <option value="Selesai">Selesai</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Pesanan (Produk) -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 bg-white">
                <div class="card-header bg-transparent border-bottom pt-4 pb-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-cart-plus text-primary me-2"></i> Keranjang Pesanan</h5>
                    <button type="button" id="btnAddItem" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold">
                        <i class="bi bi-plus"></i> Tambah Kue
                    </button>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0" id="itemsTable">
                            <thead class="border-bottom">
                                <tr class="text-muted small">
                                    <th width="45%">Pilih Kue</th>
                                    <th width="20%">Harga (@)</th>
                                    <th width="15%" class="text-center">Kuantitas</th>
                                    <th width="15%" class="text-end">Subtotal</th>
                                    <th width="5%"></th>
                                </tr>
                            </thead>
                            <tbody id="itemsBody">
                                <!-- Baris Pertama (Wajib ada 1) -->
                                <tr class="item-row border-bottom">
                                    <td class="py-3">
                                        <select name="product_id[]" class="form-select product-select bg-light border-0" required>
                                            <option value="" data-price="0">-- Pilih Kue --</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}" data-price="{{ $product->harga }}">{{ $product->nama }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="py-3">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text bg-light border-0">Rp</span>
                                            <input type="text" class="form-control product-price bg-white border-0" value="0" readonly>
                                        </div>
                                    </td>
                                    <td class="py-3 text-center">
                                        <input type="number" name="quantity[]" class="form-control form-control-sm text-center quantity-input bg-light border-0" value="1" min="1" required>
                                    </td>
                                    <td class="py-3 text-end">
                                        <div class="fw-bold text-success product-subtotal">Rp 0</div>
                                    </td>
                                    <td class="py-3 text-end">
                                        <button type="button" class="btn btn-sm btn-light text-danger btn-remove-item disabled"><i class="bi bi-trash"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end pt-4 pb-0">
                                        <h5 class="fw-bold mb-0 text-dark">Total Tagihan:</h5>
                                    </td>
                                    <td colspan="2" class="text-end pt-4 pb-0">
                                        <h4 class="fw-bold mb-0 text-success" id="grandTotalDisplay">Rp 0</h4>
                                        <input type="hidden" name="total_price" id="grandTotalInput" value="0">
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="text-end mt-4">
                <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold shadow">
                    <i class="bi bi-save me-2"></i> Simpan Pesanan
                </button>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const itemsBody = document.getElementById('itemsBody');
    const btnAddItem = document.getElementById('btnAddItem');
    const grandTotalDisplay = document.getElementById('grandTotalDisplay');
    const grandTotalInput = document.getElementById('grandTotalInput');

    // Format currency
    function formatRupiah(number) {
        return new Intl.NumberFormat('id-ID', { minimumFractionDigits: 0 }).format(number);
    }

    // Calculate Subtotal for a row
    function calculateRowSubtotal(row) {
        const select = row.querySelector('.product-select');
        const priceInput = row.querySelector('.product-price');
        const quantityInput = row.querySelector('.quantity-input');
        const subtotalDisplay = row.querySelector('.product-subtotal');

        const selectedOption = select.options[select.selectedIndex];
        const price = selectedOption ? parseFloat(selectedOption.dataset.price) : 0;
        const quantity = parseInt(quantityInput.value) || 0;
        
        const subtotal = price * quantity;
        
        priceInput.value = formatRupiah(price);
        subtotalDisplay.textContent = 'Rp ' + formatRupiah(subtotal);
        
        return subtotal;
    }

    // Calculate Grand Total
    function calculateGrandTotal() {
        let grandTotal = 0;
        document.querySelectorAll('.item-row').forEach(row => {
            grandTotal += calculateRowSubtotal(row);
        });
        
        grandTotalDisplay.textContent = 'Rp ' + formatRupiah(grandTotal);
        grandTotalInput.value = grandTotal;
    }

    // Attach Event Listeners to a row
    function attachEventListeners(row) {
        const select = row.querySelector('.product-select');
        const quantityInput = row.querySelector('.quantity-input');
        const removeBtn = row.querySelector('.btn-remove-item');

        select.addEventListener('change', calculateGrandTotal);
        quantityInput.addEventListener('input', calculateGrandTotal);
        
        if (!removeBtn.classList.contains('disabled')) {
            removeBtn.addEventListener('click', function() {
                row.remove();
                calculateGrandTotal();
            });
        }
    }

    // Initialize first row
    document.querySelectorAll('.item-row').forEach(attachEventListeners);

    // Add new row
    btnAddItem.addEventListener('click', function() {
        const firstRow = document.querySelector('.item-row');
        const newRow = firstRow.cloneNode(true);
        
        // Reset values
        newRow.querySelector('.product-select').selectedIndex = 0;
        newRow.querySelector('.product-price').value = '0';
        newRow.querySelector('.quantity-input').value = '1';
        newRow.querySelector('.product-subtotal').textContent = 'Rp 0';
        
        // Enable remove button
        const removeBtn = newRow.querySelector('.btn-remove-item');
        removeBtn.classList.remove('disabled');
        
        itemsBody.appendChild(newRow);
        attachEventListeners(newRow);
    });
});
</script>
@endpush
@endsection
