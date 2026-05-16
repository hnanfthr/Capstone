<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Label Produksi - {{ $production->id }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #fff;
            color: #000;
            font-family: 'Courier New', Courier, monospace; /* Font struk thermal */
        }
        .label-container {
            width: 100%;
            max-width: 300px; /* Ukuran printer thermal 80mm */
            margin: 0 auto;
            padding: 15px;
            border: 2px dashed #000;
        }
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            .label-container {
                border: none;
                max-width: 100%;
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
        }
        .store-name {
            font-size: 1.2rem;
            font-weight: bold;
            text-align: center;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        .divider {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }
        .fw-black {
            font-weight: 900;
        }
    </style>
</head>
<body>

    <div class="container mt-4 mb-4 text-center no-print">
        <button onclick="window.print()" class="btn btn-primary px-4 py-2 rounded-pill shadow-sm">
            <i class="bi bi-printer"></i> Cetak Label Sekarang
        </button>
        <p class="text-muted small mt-2">Pastikan pengaturan margin printer diset ke "None".</p>
    </div>

    <div class="label-container">
        <div class="store-name">Hassan's Keuken</div>
        <div class="text-center small">LABEL BATCH PRODUKSI</div>
        
        <div class="divider"></div>
        
        <div class="mb-2">
            <div class="small">Produk:</div>
            <div class="fs-5 fw-black text-uppercase">{{ $production->product ? $production->product->nama : 'N/A' }}</div>
        </div>
        
        <div class="mb-2">
            <div class="small">Kuantitas Batch:</div>
            <div class="fw-bold">{{ $production->quantity }} Toples</div>
        </div>
        
        <div class="divider"></div>
        
        <div class="mb-2">
            <div class="small">Tgl. Produksi:</div>
            <div class="fw-bold">{{ \Carbon\Carbon::parse($production->production_date)->format('d M Y') }}</div>
        </div>
        
        <div class="mb-2 p-2 border border-dark text-center">
            <div class="small">Baik Digunakan Sebelum (EXP):</div>
            <div class="fs-6 fw-black mt-1">{{ \Carbon\Carbon::parse($production->expiry_date)->format('d M Y') }}</div>
        </div>
        
        <div class="divider"></div>
        
        <div class="text-center small">
            Batch ID: #PRD-{{ str_pad($production->id, 5, '0', STR_PAD_LEFT) }}<br>
            Tercatat: {{ $production->created_at->format('d/m/Y H:i') }}
        </div>
    </div>

    <script>
        // Otomatis muncul dialog print saat halaman dimuat
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        }
    </script>
</body>
</html>
