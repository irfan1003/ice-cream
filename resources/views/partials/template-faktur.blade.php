<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Penjualan - Ice Cream</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background: #e0e0e0;
            font-family: 'Courier New', Courier, monospace;
            color: #000;
            padding: 30px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .toolbar {
            width: 100%;
            max-width: 760px;
            display: flex;
            gap: 8px;
            justify-content: flex-end;
            margin-bottom: 12px;
        }

        .btn {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            padding: 5px 14px;
            border: 1.5px solid #000;
            background: #fff;
            cursor: pointer;
        }

        .btn:hover {
            background: #000;
            color: #fff;
        }

        /* Invoice paper */
        .invoice {
            width: 100%;
            max-width: 760px;
            background: #fff;
            padding: 26px 30px 22px;
            border: 1px solid #aaa;
            box-shadow: 2px 2px 0 #bbb;
        }

        /* ─── HEADER ─── */
        .header-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0;
            margin-bottom: 4px;
        }

        .brand-name {
            font-size: 15px;
            font-weight: bold;
            text-decoration: underline;
            letter-spacing: 0.08em;
        }

        .brand-addr {
            font-size: 11.5px;
            line-height: 1.75;
            margin-top: 2px;
        }

        .kepada {
            font-size: 11.5px;
            line-height: 1.75;
        }

        /* ─── TITLE ─── */
        .title-row {
            text-align: center;
            font-size: 13.5px;
            font-weight: bold;
            text-decoration: underline;
            letter-spacing: 0.22em;
            margin: 6px 0 5px;
        }

        /* ─── META ─── */
        .meta {
            font-size: 11.5px;
            display: flex;
            justify-content: space-between;
            margin-bottom: 1px;
        }

        .meta-left {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        input[type="text"],
        input[type="date"] {
            font-family: 'Courier New', monospace;
            font-size: 11.5px;
            border: none;
            border-bottom: 1px dashed #777;
            outline: none;
            background: transparent;
            color: #000;
            padding: 0 2px;
        }

        /* ─── TABLE ─── */
        .table-wrap {
            margin-top: 7px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11.5px;
        }

        thead tr {
            border-top: 1.5px solid #000;
            border-bottom: 1.5px solid #000;
        }

        thead th {
            padding: 4px 5px;
            font-weight: bold;
            font-size: 11.5px;
            text-align: left;
            white-space: nowrap;
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
            padding: 3px 5px;
            font-size: 11.5px;
            vertical-align: middle;
            border: none;
        }

        /* editable highlight */
        [contenteditable="true"] {
            outline: none;
        }

        [contenteditable="true"]:focus {
            background: #f8f8f8;
            outline: 1px dashed #aaa;
        }

        .del-btn {
            background: none;
            border: none;
            font-size: 10px;
            color: #bbb;
            cursor: pointer;
            padding: 0 3px;
            font-family: 'Courier New', monospace;
        }

        .del-btn:hover {
            color: #900;
        }

        .add-row-btn {
            display: block;
            width: 100%;
            border: 1px dashed #bbb;
            border-top: none;
            background: none;
            padding: 3px;
            font-family: 'Courier New', monospace;
            font-size: 11px;
            color: #999;
            cursor: pointer;
            text-align: center;
        }

        .add-row-btn:hover {
            background: #f5f5f5;
            color: #000;
        }

        /* ─── DIVIDER ─── */
        .divider {
            border: none;
            border-top: 1px dashed #666;
            margin: 10px 0 8px;
        }

        /* ─── BOTTOM ─── */
        .bottom {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 16px;
            font-size: 11.5px;
            align-items: start;
        }

        .keterangan-title {
            font-weight: bold;
            font-size: 11px;
            margin-bottom: 2px;
        }

        .keterangan-sub {
            font-size: 11px;
            margin-bottom: 2px;
        }

        .notes-list {
            padding-left: 16px;
            font-size: 11px;
            line-height: 1.9;
        }

        /* Totals */
        .totals {
            min-width: 200px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            font-size: 11.5px;
            line-height: 1.85;
        }

        .total-row .lbl {
            white-space: nowrap;
            margin-right: 8px;
        }

        .total-row .val {
            text-align: right;
            min-width: 65px;
        }

        .total-row.grand {
            border-top: 1.5px solid #000;
            padding-top: 3px;
            margin-top: 2px;
            font-weight: bold;
        }

        /* ─── SIGNATURE ─── */
        .sig-section {
            display: flex;
            justify-content: flex-end;
            margin-top: 22px;
        }

        .sig-block {
            text-align: center;
            width: 180px;
        }

        .sig-label {
            font-size: 11.5px;
            text-align: left;
            padding-left: 8px;
            margin-bottom: 52px;
        }

        .sig-line {
            border-top: 1px solid #000;
            margin: 0 8px;
        }

        .sig-name {
            font-size: 11px;
            margin-top: 4px;
            text-align: center;
        }

        /* ─── PRINT ─── */
        @media print {
            body {
                background: #fff;
                padding: 0;
            }

            .toolbar {
                display: none;
            }

            .invoice {
                box-shadow: none;
                border: none;
            }

            .add-row-btn {
                display: none;
            }

            .del-btn {
                display: none;
            }

            .no-print {
                display: none !important;
            }

            [contenteditable]:focus {
                background: none;
                outline: none;
            }
        }
    </style>
</head>

<body>

    <div class="toolbar no-print">
        <button class="btn" onclick="addRow()">+ Tambah Baris</button>
        <button class="btn" onclick="window.print()">Cetak</button>
    </div>

    <div class="invoice">

        <!-- Header -->
        <div class="header-grid">
            <div>
                <div class="brand-name">I C E &nbsp; C R E A M</div>
                <div class="brand-addr">
                    JL MUJAHIR METRO TIMUR<br>
                    Tel p : <span contenteditable="true">082184846969</span>
                </div>
            </div>
            <div class="kepada">
                Kepada Yth. :<br>
                <span contenteditable="true">ABDUL JALIL/ R8B/ BUMI NABUNG/</span><br>
                <span contenteditable="true">BUMI NABUNG ILIR</span>
            </div>
        </div>

        <!-- Title -->
        <div class="title-row">ORDER &nbsp; PENJUALAN</div>

        <!-- Meta row 1 -->
        <div class="meta">
            <div class="meta-left">
                <span>Tanggal : <input type="date" id="inv-date" style="width:120px;"></span>
                &nbsp;
                <span>No. <input type="text" id="inv-no" value="DOSC00012150" style="width:115px;"></span>
            </div>
            <div>Sales/Cpr : <input type="text" value="0001 /sa" style="width:75px;"></div>
        </div>

        <!-- Meta row 2 -->
        <div class="meta">
            <span>Customer PO : <input type="text" placeholder="" style="width:160px;"></span>
        </div>

        <!-- Table -->
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th style="width:70px;">KODE</th>
                        <th>NAMA BARANG</th>
                        <th class="c" style="width:70px;">BANYAKNYA</th>
                        <th class="c" style="width:36px;"></th>
                        <th class="r" style="width:75px;">HARGA</th>
                        <th class="r" style="width:42px;">DISC%</th>
                        <th class="r" style="width:75px;">SUBTOTAL</th>
                        <th style="width:18px;" class="no-print"></th>
                    </tr>
                </thead>
                <tbody id="items-body">
                    <tr>
                        <td contenteditable="true">001340</td>
                        <td contenteditable="true">COFFE CRISPY (40) PA</td>
                        <td class="c" contenteditable="true">1</td>
                        <td class="c" contenteditable="true">DUS</td>
                        <td class="r" contenteditable="true">125,000</td>
                        <td class="r" contenteditable="true"></td>
                        <td class="r sub">125,000</td>
                        <td class="no-print"><button class="del-btn" onclick="delRow(this)">x</button></td>
                    </tr>
                    <tr>
                        <td contenteditable="true">001074</td>
                        <td contenteditable="true">CHOCOLATE CORN (40) K</td>
                        <td class="c" contenteditable="true">1</td>
                        <td class="c" contenteditable="true">DUS</td>
                        <td class="r" contenteditable="true">104,000</td>
                        <td class="r" contenteditable="true"></td>
                        <td class="r sub">104,000</td>
                        <td class="no-print"><button class="del-btn" onclick="delRow(this)">x</button></td>
                    </tr>
                    <tr>
                        <td contenteditable="true">001322</td>
                        <td contenteditable="true">MIDI CHO BRONIE 80ML (22)</td>
                        <td class="c" contenteditable="true">1</td>
                        <td class="c" contenteditable="true">DUS</td>
                        <td class="r" contenteditable="true">127,000</td>
                        <td class="r" contenteditable="true"></td>
                        <td class="r sub">127,000</td>
                        <td class="no-print"><button class="del-btn" onclick="delRow(this)">x</button></td>
                    </tr>
                    <tr>
                        <td contenteditable="true">1078</td>
                        <td contenteditable="true">SUNDAE STRAWBERRY PA (24)</td>
                        <td class="c" contenteditable="true">1</td>
                        <td class="c" contenteditable="true">DUS</td>
                        <td class="r" contenteditable="true">107,000</td>
                        <td class="r" contenteditable="true"></td>
                        <td class="r sub">107,000</td>
                        <td class="no-print"><button class="del-btn" onclick="delRow(this)">x</button></td>
                    </tr>
                    <tr>
                        <td contenteditable="true">001118</td>
                        <td contenteditable="true">STICK MANGGO (50) G</td>
                        <td class="c" contenteditable="true">1</td>
                        <td class="c" contenteditable="true">DUS</td>
                        <td class="r" contenteditable="true">80,000</td>
                        <td class="r" contenteditable="true"></td>
                        <td class="r sub">80,000</td>
                        <td class="no-print"><button class="del-btn" onclick="delRow(this)">x</button></td>
                    </tr>
                    <tr>
                        <td contenteditable="true">1062</td>
                        <td contenteditable="true">SWEET CORN PA (40)</td>
                        <td class="c" contenteditable="true">1</td>
                        <td class="c" contenteditable="true">DUS</td>
                        <td class="r" contenteditable="true">104,000</td>
                        <td class="r" contenteditable="true"></td>
                        <td class="r sub">104,000</td>
                        <td class="no-print"><button class="del-btn" onclick="delRow(this)">x</button></td>
                    </tr>
                </tbody>
            </table>
            <button class="add-row-btn no-print" onclick="addRow()">+ Tambah Produk</button>
        </div>

        <hr class="divider">

        <!-- Bottom -->
        <div class="bottom">
            <div class="notes">
                <div class="keterangan-title">KETERANGAN:</div>
                <div class="keterangan-sub">Perhatian:</div>
                <ol class="notes-list">
                    <li contenteditable="true">NO. REK BRI 013001003756302 a.n. CV. PRIMA AMANAH</li>
                    <li contenteditable="true">HARGA SUDAH TERMASUK PAJAK 11%</li>
                    <li contenteditable="true">KRITIK DAN SARAN HUB. 0823-7578-3888</li>
                </ol>
            </div>

            <div class="totals">
                <div class="total-row">
                    <span class="lbl">SubTotal : Rp</span>
                    <span class="val" id="subtotal-val">647,000</span>
                </div>
                <div class="total-row">
                    <span class="lbl">Disc : Rp</span>
                    <span class="val" id="disc-val">0</span>
                </div>
                <div class="total-row">
                    <span class="lbl">Lain-lain : Rp</span>
                    <span class="val" id="lain-val">0</span>
                </div>
                <div class="total-row">
                    <span class="lbl">PPN : Rp</span>
                    <span class="val" id="ppn-val">0</span>
                </div>
                <div class="total-row grand">
                    <span class="lbl">Total : Rp</span>
                    <span class="val" id="total-val">647,000</span>
                </div>
            </div>
        </div>

        <!-- Signature -->
        <div class="sig-section">
            <div class="sig-block">
                <div class="sig-label">Direktur,</div>
                <div class="sig-line"></div>
                <div class="sig-name" contenteditable="true">(
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    )</div>
            </div>
        </div>

    </div>

    <script>
        // Set today's date
        const d = new Date();
        document.getElementById('inv-date').value =
            `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')}`;

        function parseNum(s) {
            return parseFloat((s || '').replace(/[^0-9.-]/g, '')) || 0;
        }

        function fmt(n) {
            return Math.round(n).toLocaleString('id-ID');
        }

        function recalc() {
            let total = 0;
            document.querySelectorAll('#items-body tr').forEach(row => {
                const td = row.querySelectorAll('td');
                const qty = parseNum(td[2].innerText) || 1;
                const harga = parseNum(td[4].innerText);
                const disc = parseNum(td[5].innerText);
                const sub = harga * qty * (1 - disc / 100);
                td[6].innerText = fmt(sub);
                total += sub;
            });
            document.getElementById('subtotal-val').innerText = fmt(total);
            document.getElementById('total-val').innerText = fmt(total);
        }

        document.getElementById('items-body').addEventListener('input', recalc);

        function addRow() {
            const tbody = document.getElementById('items-body');
            const tr = document.createElement('tr');
            tr.innerHTML = `
      <td contenteditable="true">000000</td>
      <td contenteditable="true">Nama Produk</td>
      <td class="c" contenteditable="true">1</td>
      <td class="c" contenteditable="true">DUS</td>
      <td class="r" contenteditable="true">0</td>
      <td class="r" contenteditable="true"></td>
      <td class="r sub">0</td>
      <td class="no-print"><button class="del-btn" onclick="delRow(this)">x</button></td>
    `;
            tbody.appendChild(tr);
            tr.querySelector('[contenteditable]').focus();
            recalc();
        }

        function delRow(btn) {
            const tbody = document.getElementById('items-body');
            if (tbody.rows.length > 1) {
                btn.closest('tr').remove();
                recalc();
            }
        }

        recalc();
    </script>
</body>

</html>
