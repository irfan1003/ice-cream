<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Dokumen</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: #f8fafc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            max-width: 500px;
            width: 100%;
            padding: 40px 30px;
            text-align: center;
        }

        .status-badge {
            padding: 16px 32px;
            border-radius: 999px;
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 24px;
        }

        .status-badge.valid {
            background: #dcfce7;
            color: #166534;
            border: 2px solid #86efac;
        }

        .status-badge.invalid {
            background: #fee2e2;
            color: #991b1b;
            border: 2px solid #fca5a5;
        }

        .icon {
            width: 64px;
            height: 64px;
            border-radius: 999px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
        }

        .icon.valid {
            background: #dcfce7;
            color: #22c55e;
        }

        .icon.invalid {
            background: #fee2e2;
            color: #ef4444;
        }

        .icon svg {
            width: 32px;
            height: 32px;
        }

        .title {
            font-size: 20px;
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 8px;
        }

        .subtitle {
            color: #64748b;
            margin-bottom: 32px;
            line-height: 1.5;
        }

        .details {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            text-align: left;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px dashed #cbd5e1;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            color: #64748b;
            font-size: 14px;
            font-weight: 500;
        }

        .detail-value {
            color: #1e293b;
            font-size: 14px;
            font-weight: 600;
            text-align: right;
        }

        @media (max-width: 480px) {
            .card {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="card">
        @if($status === 'valid')
            <div class="icon valid">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <div class="status-badge valid">DOKUMEN VALID</div>
            <h1 class="title">Faktur Penjualan Asli</h1>
            <p class="subtitle">Dokumen ini telah diverifikasi dan disetujui oleh Direktur</p>

            <div class="details">
                <div class="detail-row">
                    <span class="detail-label">Nomor Faktur</span>
                    <span class="detail-value">{{ $order->order_number }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Nama Pelanggan</span>
                    <span class="detail-value">{{ $order->customer->customer_name ?? '-' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Waktu Persetujuan</span>
                    <span class="detail-value">{{ \Carbon\Carbon::parse($order->updated_at)->format('d/m/Y H:i') }}</span>
                </div>
            </div>
        @else
            <div class="icon invalid">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>
            <div class="status-badge invalid">DOKUMEN TIDAK VALID</div>
            <h1 class="title">Verifikasi Gagal</h1>
            <p class="subtitle">Data dokumen tidak ditemukan dalam sistem</p>
        @endif
    </div>
</body>
</html>