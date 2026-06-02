<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview Faktur - #{{ $order->order_number }}</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background: {{ isset($isPdf) ? '#fff' : '#f1f5f9' }};
            font-family: 'Courier New', Courier, monospace;
            color: #000;
            padding: {{ isset($isPdf) ? '0' : '40px 20px' }};
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .no-print-zone {
            width: 100%;
            max-width: 800px;
            margin-bottom: 20px;
            display: {{ isset($isPdf) ? 'none' : 'flex' }};
            justify-content: space-between;
            align-items: center;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            color: #64748b;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.2s;
            font-family: sans-serif;
        }

        .back-btn:hover {
            background: #f8fafc;
            color: #0f172a;
            border-color: #cbd5e1;
        }

        .print-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: #0f172a;
            border: none;
            border-radius: 8px;
            color: #fff;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            transition: opacity 0.2s;
            font-family: sans-serif;
        }

        .print-btn:hover {
            opacity: 0.9;
        }

        /* Invoice paper */
        .invoice {
            width: 100%;
            max-width: 800px;
            background: #fff;
            padding: 40px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        }

        /* ─── HEADER ─── */
        .header-grid {
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            gap: 40px;
            margin-bottom: 20px;
        }

        .brand-name {
            font-size: 18px;
            font-weight: bold;
            text-decoration: underline;
            letter-spacing: 0.1em;
            margin-bottom: 8px;
        }

        .brand-addr {
            font-size: 12px;
            line-height: 1.6;
            color: #334155;
        }

        .kepada {
            font-size: 12px;
            line-height: 1.6;
            text-align: right;
        }

        .kepada-val {
            font-weight: bold;
            text-decoration: underline;
        }

        /* ─── TITLE ─── */
        .title-row {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            text-decoration: underline;
            letter-spacing: 0.2em;
            margin: 20px 0 15px;
        }

        /* ─── META ─── */
        .meta {
            font-size: 12px;
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
            border-bottom: 1px dashed #cbd5e1;
            padding-bottom: 4px;
        }

        .meta-group {
            display: flex;
            gap: 20px;
        }

        /* ─── TABLE ─── */
        .table-wrap {
            margin-top: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        thead tr {
            border-top: 2px solid #0f172a;
            border-bottom: 2px solid #0f172a;
        }

        thead th {
            padding: 10px 8px;
            font-weight: bold;
            text-align: left;
        }

        th.c,
        td.c {
            text-align: center;
        }

        th.r,
        td.r {
            text-align: right;
        }

        tbody td {
            padding: 8px;
            border-bottom: 1px solid #f1f5f9;
        }

        .bonus-tag {
            font-size: 9px;
            font-weight: bold;
            color: #059669;
            background: #ecfdf5;
            padding: 1px 4px;
            border-radius: 4px;
            text-transform: uppercase;
            margin-left: 4px;
        }

        /* ─── BOTTOM ─── */
        .bottom {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 40px;
            margin-top: 20px;
            font-size: 12px;
        }

        .keterangan-title {
            font-weight: bold;
            margin-bottom: 4px;
        }

        .notes-list {
            padding-left: 20px;
            line-height: 1.8;
            color: #475569;
        }

        /* Totals */
        .totals {
            min-width: 250px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            line-height: 2;
        }

        .total-row .val {
            text-align: right;
            font-weight: 500;
        }

        .total-row.grand {
            border-top: 2px solid #0f172a;
            margin-top: 8px;
            padding-top: 4px;
            font-weight: bold;
            font-size: 14px;
        }

        /* ─── SIGNATURE ─── */
        .sig-section {
            display: flex;
            justify-content: flex-end;
            margin-top: 40px;
        }

        .sig-block {
            text-align: center;
            width: 200px;
        }

        .sig-label {
            font-size: 12px;
            margin-bottom: 5px;
            text-align: left;
        }

        .sig-line {
            border-top: 1px solid #0f172a;
            margin-bottom: 4px;
        }

        .sig-name {
            font-size: 12px;
        }

        @media print {
            body {
                background: #fff;
                padding: 0;
            }

            .no-print-zone {
                display: none;
            }

            .invoice {
                box-shadow: none;
                border: none;
                max-width: 100%;
                padding: 0;
            }
        }
    </style>
</head>

<body>

    @if (!isset($isPdf))
        <div class="no-print-zone">
            <a href="{{ route('direktur.verification.orders') }}" class="back-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
            <button onclick="window.print()" class="print-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Cetak Faktur
            </button>
        </div>
    @endif

    <div class="invoice">
        <div class="header-grid">
            <div>
                <div class="brand-name">I C E &nbsp; C R E A M</div>
                <div class="brand-addr">
                    JL MUJAHIR METRO TIMUR<br>
                    Telp : 082184846969
                </div>
            </div>
            <div class="kepada">
                Kepada Yth. :<br>
                <span class="kepada-val">{{ $order->customer->customer_name }}</span><br>
                {{ $order->customer->address ?? '-' }} {{ $order->customer->zone->zone_name ?? '-' }}<br>
                {{ $order->customer->phone ?? '' }}
            </div>
        </div>

        <div class="title-row">ORDER &nbsp; PENJUALAN</div>

        <div class="meta">
            <div class="meta-group">
                <span>Tanggal : <strong>{{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y') }}</strong></span>
                <span>No. <strong>{{ $order->order_number }}</strong></span>
            </div>
            <div>Sales/Cpr : <strong>{{ $order->sales->name }}</strong></div>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th style="width:100px;">KODE</th>
                        <th>NAMA BARANG</th>
                        <th class="c" style="width:80px;">BANYAKNYA</th>
                        <th class="r" style="width:120px;">HARGA</th>
                        <th class="r" style="width:80px;">DISC%</th>
                        <th class="r" style="width:120px;">SUBTOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->orderDetail as $item)
                        <tr>
                            <td>{{ $item->product->id_product }}</td>
                            <td>
                                {{ $item->product->product_name }}
                                @if ($item->bonus_qty > 0)
                                    <span class="bonus-tag">Bonus: {{ $item->bonus_qty }}</span>
                                @endif
                            </td>
                            <td class="c">{{ $item->qty }}</td>
                            <td class="r">{{ number_format($item->price_at_time, 0, ',', '.') }}</td>
                            <td class="r">{{ $item->discount ? $item->discount . '%' : '-' }}</td>
                            <td class="r">{{ number_format($item->total_item_price, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="bottom">
            <div class="notes">
                <div class="keterangan-title">KETERANGAN:</div>
                <ol class="notes-list">
                    <li>NO. REK BRI 013001003756302 a.n. CV. PRIMA AMANAH</li>
                    <li>HARGA SUDAH TERMASUK PAJAK 11%</li>
                    <li>KRITIK DAN SARAN HUB. 0823-7578-3888</li>
                </ol>
            </div>

            <div class="totals">
                <div class="total-row">
                    <span>SubTotal :</span>
                    <span class="val">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="total-row">
                    <span>Diskon :</span>
                    <span class="val">Rp {{ number_format($order->discount_total ?? 0, 0, ',', '.') }}</span>
                </div>
                <div class="total-row">
                    <span>PPN :</span>
                    <span class="val">Rp {{ number_format($order->tax_amount ?? 0, 0, ',', '.') }}</span>
                </div>
                <div class="total-row grand">
                    <span>Total :</span>
                    <span class="val">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <div class="sig-section">
            <div class="sig-block">
                <div class="sig-label">Direktur,</div>
                <div style="height: 60px; margin-bottom: 5px; text-align: center;">
                    @if (isset($signature))
                        <img src="{{ $signature }}" style="height: 70px; width: auto; margin-top: -20px;">
                    @endif
                </div>
                <div class="sig-line"></div>
                <div class="sig-name">(
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    )</div>
            </div>
        </div>
    </div>
</body>

</html>
