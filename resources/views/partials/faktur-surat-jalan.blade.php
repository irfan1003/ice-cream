<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Surat Pengiriman Barang</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Courier New", monospace;
            background: #f8fafc;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 5px;
            position: relative;
        }

        .watermark {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 48px;
            color: rgba(0, 0, 0, 0.1);
            font-weight: bold;
            transform: rotate(-15deg);
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #9575cd;
            padding-bottom: 10px;
        }

        .header h1 {
            color: #5e35b1;
            font-size: 20px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .header .doc-number {
            color: #7e57c2;
            font-size: 12px;
            margin-top: 5px;
        }

        .info-section {
            margin-bottom: 20px;
            color: #5e35b1;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 13px;
        }

        .info-label {
            font-weight: bold;
            color: #7e57c2;
        }

        .divider {
            border-bottom: 1px solid #b39ddb;
            margin: 15px 0;
        }

        .product-section {
            margin: 20px 0;
        }

        .product-label {
            color: #5e35b1;
            font-size: 13px;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #ffffff;
            font-size: 12px;
        }

        th {
            background: #f1f5f9;
            color: #4527a0;
            padding: 8px;
            text-align: center;
            border: 1px solid #b39ddb;
            font-size: 11px;
        }

        td {
            padding: 8px;
            border: 1px solid #b39ddb;
            text-align: center;
            color: #4a148c;
        }

        td:first-child {
            text-align: center;
            width: 40px;
        }

        td:nth-child(2) {
            text-align: left;
        }

        .total-section {
            margin-top: 15px;
            text-align: right;
            color: #5e35b1;
            font-size: 13px;
        }

        .total-row {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 5px;
        }

        .total-label {
            width: 180px;
            text-align: right;
            margin-right: 20px;
        }

        .total-value {
            width: 120px;
            text-align: right;
            font-weight: bold;
        }

        .grand-total {
            border-top: 2px solid #7e57c2;
            padding-top: 8px;
            margin-top: 8px;
            font-weight: bold;
            font-size: 14px;
        }

        .notes {
            margin-top: 20px;
            color: #5e35b1;
            font-size: 12px;
            line-height: 1.6;
        }

        .notes-title {
            font-weight: bold;
            margin-bottom: 5px;
            color: #7e57c2;
        }

        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
        }

        .signature-box {
            text-align: center;
            width: 30%;
        }

        .signature-label {
            color: #7e57c2;
            font-size: 13px;
            margin-bottom: 60px;
        }

        .signature-line {
            border-bottom: 1px solid #9575cd;
            width: 80%;
            margin: 0 auto 5px;
        }

        .signature-name {
            color: #5e35b1;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="watermark">8</div>

        <div class="header">
            <h1>SURAT PENGIRIMAN BARANG</h1>
            <div class="doc-number">{{ $delivery->spb_number }}</div>
        </div>

        <div class="info-section">
            <div class="info-row">
                <div>
                    <span class="info-label">PRIMA AMANAH</span><br />
                    <span>Jln. Kc Ajeng Tita Kota Metro</span><br />
                    <span>No. Hp 082016701280</span><br />
                    <span>085768440555</span>
                </div>
                <div style="text-align: right">
                    <span class="info-label">Tanggal</span>
                    <span>{{ \Carbon\Carbon::parse($delivery->created_at)->format('d/m/y') }}</span>
                </div>
            </div>
        </div>

        <div class="divider"></div>

        <div class="product-section">
            <div class="product-label">
                <span class="info-label">Nama Toko/Outlet:</span> {{ $delivery->order->customer->customer_name }}
                <span style="float: right"><span class="info-label">Rute:</span>
                    <u>{{ $delivery->order->customer->zone->zone_name ?? '-' }}</u></span>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Produk</th>
                        <th>Satuan</th>
                        <th>Harga Rp</th>
                        <th>Qty</th>
                        <th>Bonus</th>
                        <th>Disk 1 (%)</th>
                        <th>Disk2 Rp</th>
                        <th>Subtotal Rp</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($delivery->order->orderDetail as $index => $detail)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td style="text-align: left">{{ $detail->product->product_name }}</td>
                            <td>{{ $detail->product->unit ?? 'PCS' }}</td>
                            <td>{{ number_format($detail->price_at_time, 0, ',', '.') }}</td>
                            <td>{{ $detail->qty }}</td>
                            <td>{{ $detail->bonus_qty }}</td>
                            <td>{{ number_format($detail->discount, 0, ',', '.') }}</td>
                            <td>0.0</td>
                            <td>{{ number_format($detail->total_item_price, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="total-section">
            <div class="total-row">
                <div class="total-label">Total Jumlah</div>
                <div class="total-value">{{ $delivery->order->orderDetail->sum('qty') }}
                    {{ $delivery->order->orderDetail->first()->product->unit ?? 'PCS' }}</div>
            </div>
            <div class="total-row">
                <div class="total-label">Sub Total Rp</div>
                <div class="total-value">{{ number_format($delivery->order->subtotal, 0, ',', '.') }}</div>
            </div>
            <div class="total-row">
                <div class="total-label">Diskon Rp</div>
                <div class="total-value">{{ number_format($delivery->order->discount_total, 0, ',', '.') }}</div>
            </div>
            <div class="total-row">
                <div class="total-label">Diskon Belanja Rp</div>
                <div class="total-value">0</div>
            </div>
            <div class="total-row">
                <div class="total-label">Total Diskon Final Rp</div>
                <div class="total-value">{{ number_format($delivery->order->discount_total, 0, ',', '.') }}</div>
            </div>
            <div class="total-row">
                <div class="total-label">Total Pajak Rp</div>
                <div class="total-value">{{ number_format($delivery->order->tax_amount, 0, ',', '.') }}</div>
            </div>
            <div class="total-row grand-total">
                <div class="total-label">Grand Total Rp</div>
                <div class="total-value">{{ number_format($delivery->order->grand_total, 0, ',', '.') }}</div>
            </div>
        </div>

        <div class="notes">
            <div class="notes-title">NOTE:</div>
            <div>1. Dilarang memberikan tips kepada karyawan kami.</div>
            <div>2. Kritik dan saran hubungi 082375783888</div>
        </div>

        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-label">Pengirim</div>
                <div class="signature-line"></div>
                <div class="signature-name">( )</div>
            </div>
            <div class="signature-box">
                <div class="signature-label">Penerima</div>
                <div class="signature-line"></div>
                <div class="signature-name">( )</div>
            </div>
        </div>
    </div>
</body>

</html>