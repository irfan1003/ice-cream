<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Surat Jalan</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Courier New", monospace;
            background: linear-gradient(135deg, #e8d5f2 0%, #d4e5f7 100%);
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: linear-gradient(135deg, #f3e5ff 0%, #e3f2fd 100%);
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
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

        .info-detail {
            line-height: 1.6;
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
            background: rgba(255, 255, 255, 0.5);
            font-size: 12px;
        }

        th {
            background: rgba(149, 117, 205, 0.3);
            color: #4527a0;
            padding: 10px 8px;
            text-align: center;
            border: 1px solid #b39ddb;
            font-size: 12px;
        }

        td {
            padding: 10px 8px;
            border: 1px solid #b39ddb;
            text-align: center;
            color: #4a148c;
        }

        td:first-child {
            text-align: center;
            width: 50px;
        }

        td:nth-child(2) {
            text-align: left;
        }

        .summary-section {
            margin-top: 20px;
            padding: 15px;
            background: rgba(255, 255, 255, 0.3);
            border: 1px solid #b39ddb;
            border-radius: 5px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            color: #5e35b1;
            font-size: 13px;
        }

        .summary-label {
            font-weight: bold;
            color: #7e57c2;
        }

        .summary-value {
            font-weight: bold;
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
            margin-top: 50px;
            padding: 0 40px;
        }

        .signature-box {
            text-align: center;
            width: 40%;
        }

        .signature-label {
            color: #7e57c2;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .signature-img-box {
            height: 75px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .signature-img-box img {
            max-height: 70px;
            object-fit: contain;
        }

        .signature-line {
            border-bottom: 1px solid #9575cd;
            width: 100%;
            margin: 0 auto 5px;
        }

        .signature-name {
            color: #5e35b1;
            font-size: 12px;
        }

        .destination-info {
            background: rgba(255, 255, 255, 0.4);
            padding: 12px;
            border-radius: 5px;
            border: 1px solid #b39ddb;
            margin-bottom: 15px;
        }

        .destination-row {
            display: flex;
            margin-bottom: 5px;
            font-size: 13px;
            color: #5e35b1;
        }

        .destination-row .label {
            width: 150px;
            font-weight: bold;
            color: #7e57c2;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="watermark">SJ</div>

        <div class="header">
            <h1>SURAT JALAN</h1>
            <div class="doc-number">
                SJ/{{ now()->format('Y/m/d') }}/{{ $order->id_order }}</div>
        </div>

        <div class="info-section">
            <div class="info-row">
                <div class="info-detail">
                    <span class="info-label">PRIMA AMANAH</span><br />
                    <span>Jln. Kc Ajeng Tita Kota Metro</span><br />
                    <span>No. Hp 082016701280</span><br />
                    <span>085768440555</span>
                </div>
                <div style="text-align: right">
                    <span class="info-label">Tanggal:</span>
                    <span>{{ now()->format('d/m/y') }}</span><br />
                    <span class="info-label">Jam:</span>
                    <span>{{ now()->format('H:i') }} WIB</span>
                </div>
            </div>
        </div>

        <div class="divider"></div>

        <div class="destination-info">
            <div class="destination-row">
                <div class="label">Tujuan Pengiriman:</div>
                <div>{{ $order->customer->customer_name }}</div>
            </div>
            <div class="destination-row">
                <div class="label">Alamat Tujuan:</div>
                <div>{{ $order->customer->address ?? '-' }}</div>
            </div>
            <div class="destination-row">
                <div class="label">Rute:</div>
                <div><u>{{  $order->customer->zone?->zone_name ?? '' }}</u></div>
            </div>
            <!-- <div class="destination-row">
                <div class="label">Kendaraan:</div>
                <div>-</div>
            </div>
            <div class="destination-row">
                <div class="label">Sopir/Pengantar:</div>
                <div>-</div>
            </div> -->
        </div>

        <div class="product-section">
            <div class="product-label">
                <span class="info-label">Daftar Barang Yang Dikirim:</span>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Produk</th>
                        <th>Satuan</th>
                        <th>Jumlah</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderDetail as $index => $detail)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td style="text-align: left">{{ $detail->product->product_name }}</td>
                            <td>{{ $detail->product->unit ?? 'DUS' }}</td>
                            <td>{{ $detail->qty + $detail->bonus_qty }}</td>
                            <td>{{ $detail->bonus_qty > 0 ? $detail->bonus_qty . ' Bonus' : '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="summary-section">
            <div class="summary-row">
                <div class="summary-label">Total Jenis Barang:</div>
                <div class="summary-value">{{ $order->orderDetail->count() }} Item</div>
            </div>
            <div class="summary-row">
                <div class="summary-label">Total Jumlah:</div>
                <div class="summary-value">
                    {{ $order->orderDetail->sum(function ($d) {
    return $d->qty + $d->bonus_qty; }) }} Item
                </div>
            </div>
        </div>

        <div class="notes">
            <div class="notes-title">CATATAN:</div>
            <div>
                1. Barang yang sudah dikirim tidak dapat dikembalikan kecuali ada
                kesalahan dari pihak kami.
            </div>
            <div>2. Harap periksa kondisi barang saat penerimaan.</div>
            <div>3. Untuk informasi dan keluhan hubungi 082375783888</div>
        </div>

        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-label">Admin Kantor</div>
                <div class="signature-img-box">
                    @if(isset($delivery) && !in_array($delivery->delivery_status, ['pending_admin_kantor', 'ditolak']) && isset($adminKantor) && $adminKantor->signature)
                        <img src="{{ asset('storage/' . $adminKantor->signature) }}?t={{ time() }}"
                            alt="Signature Admin Kantor">
                    @endif
                </div>
                <div class="signature-line"></div>
                <div class="signature-name">
                    ({{ isset($adminKantor) && isset($delivery) && !in_array($delivery->delivery_status, ['pending_admin_kantor', 'ditolak']) ? $adminKantor->name : '           ' }})
                </div>
            </div>
            <div class="signature-box">
                <div class="signature-label">Admin Gudang</div>
                <div class="signature-img-box">
                    @if(isset($delivery) && in_array($delivery->delivery_status, ['ready', 'shipped', 'delivered']) && isset($adminGudang) && $adminGudang->signature)
                        <img src="{{ asset('storage/' . $adminGudang->signature) }}?t={{ time() }}"
                            alt="Signature Admin Gudang">
                    @endif
                </div>
                <div class="signature-line"></div>
                <div class="signature-name">
                    ({{ isset($adminGudang) && isset($delivery) && in_array($delivery->delivery_status, ['ready', 'completed']) ? $adminGudang->name : '           ' }})
                </div>
            </div>
        </div>
    </div>
</body>

</html>