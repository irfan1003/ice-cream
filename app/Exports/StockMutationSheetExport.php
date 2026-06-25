<?php

namespace App\Exports;

use App\Models\OrderDetail;
use App\Models\Product;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StockMutationSheetExport implements FromArray, ShouldAutoSize, WithStyles, WithTitle
{
    protected Carbon $startDate;
    protected Carbon $endDate;
    protected ?string $brand;
    protected string $sheetName;

    public function __construct(Carbon $startDate, Carbon $endDate, ?string $brand, string $sheetName)
    {
        $this->startDate = $startDate;
        $this->endDate   = $endDate;
        $this->brand     = $brand;
        $this->sheetName = $sheetName;
    }

    public function title(): string
    {
        return $this->sheetName;
    }

    /**
     * Membangun array 2 dimensi yang akan ditulis ke spreadsheet.
     */
    public function array(): array
    {
        $query = Product::orderBy('product_name');
        if (is_null($this->brand) || trim($this->brand) === '') {
            $query->where(function($q) {
                $q->whereNull('brand')->orWhere('brand', '');
            });
        } else {
            $query->where('brand', $this->brand);
        }
        $products = $query->get();
        $productIds = $products->pluck('id_product')->toArray();

        $startDate = $this->startDate;
        $endDate   = $this->endDate;

        // Buat daftar tanggal
        $dates       = [];
        $currentDate = $startDate->copy();
        while ($currentDate->lte($endDate)) {
            $dates[] = $currentDate->copy();
            $currentDate->addDay();
        }

        // Ambil data order detail
        $orderDetails = OrderDetail::with(['order'])
            ->whereIn('product_id', $productIds)
            ->whereHas('order', function ($q) use ($startDate, $endDate) {
                $q->where('status', 'paid')
                  ->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->get();

        // Susun data mutasi [product_id][date_key] = [ord, bns, disc]
        $mutationData = [];
        foreach ($orderDetails as $detail) {
            $pid     = $detail->product_id;
            $dateKey = Carbon::parse($detail->order->created_at)->format('Y-m-d');

            if (!isset($mutationData[$pid][$dateKey])) {
                $mutationData[$pid][$dateKey] = ['ord' => 0, 'bns' => 0, 'disc' => 0];
            }
            $mutationData[$pid][$dateKey]['ord']  += $detail->qty;
            $mutationData[$pid][$dateKey]['bns']  += $detail->bonus_qty ?? 0;
            $mutationData[$pid][$dateKey]['disc'] += $detail->discount ?? 0;
        }

        // Hitung stok awal per produk
        $soldAfterStart = OrderDetail::whereIn('product_id', $productIds)
            ->whereHas('order', function ($q) use ($startDate) {
                $q->where('status', 'paid')->where('created_at', '>=', $startDate);
            })
            ->selectRaw('product_id, SUM(qty) as total_qty')
            ->groupBy('product_id')
            ->pluck('total_qty', 'product_id');

        // ======================================
        // BANGUN ROWS
        // ======================================
        $rows = [];

        // --- ROW 1: Judul ---
        $brandTitle = $this->sheetName === 'Lainnya' ? 'TANPA BRAND' : strtoupper($this->sheetName);
        $titleRow = ['LAPORAN MUTASI STOK - ' . $brandTitle];
        for ($i = 1; $i < 2 + count($dates) * 3 + 2; $i++) {
            $titleRow[] = '';
        }
        $rows[] = $titleRow;

        // --- ROW 2: Sub-judul periode ---
        $periodeRow = ['Periode: ' . $startDate->format('d/m/Y') . ' s/d ' . $endDate->format('d/m/Y')];
        for ($i = 1; $i < 2 + count($dates) * 3 + 2; $i++) {
            $periodeRow[] = '';
        }
        $rows[] = $periodeRow;

        // --- ROW 3 (blank) ---
        // Catatan: JANGAN gunakan [] karena maatwebsite/excel akan skip baris kosong.
        // Gunakan [''] agar baris benar-benar tercipta di Excel.
        $rows[] = [''];

        // --- ROW 4: Header baris 1 ---
        $header1 = ['NAMA ITEM', 'STOK AWAL'];
        foreach ($dates as $date) {
            $header1[] = $date->format('d/m/Y'); // kolom pertama dari group 3
            $header1[] = '';
            $header1[] = '';
        }
        $header1[] = 'SUB TOTAL ORD';
        $header1[] = 'SUB TOTAL BNS';
        $rows[] = $header1;

        // --- ROW 5: Header baris 2 (sub-kolom) ---
        $header2 = ['', '']; // NAMA ITEM, STOK AWAL
        foreach ($dates as $date) {
            $header2[] = 'ORD';
            $header2[] = 'BNS';
            $header2[] = 'DISC';
        }
        $header2[] = '';
        $header2[] = '';
        $rows[] = $header2;

        // --- ROW 6+: Data produk ---
        foreach ($products as $product) {
            $pid      = $product->id_product;
            $stokAwal = ($product->current_stock ?? 0) + ($soldAfterStart[$pid] ?? 0);

            $row = [$product->product_name, $stokAwal];

            $totalOrd = 0;
            $totalBns = 0;

            foreach ($dates as $date) {
                $dateKey = $date->format('Y-m-d');
                $ord     = $mutationData[$pid][$dateKey]['ord']  ?? 0;
                $bns     = $mutationData[$pid][$dateKey]['bns']  ?? 0;
                $disc    = $mutationData[$pid][$dateKey]['disc'] ?? 0;

                $row[] = $ord ?: '';
                $row[] = $bns ?: '';
                $row[] = $disc ?: '';

                $totalOrd += $ord;
                $totalBns += $bns;
            }

            $row[] = $totalOrd ?: '';
            $row[] = $totalBns ?: '';

            $rows[] = $row;
        }

        return $rows;
    }

    /**
     * Styling spreadsheet: header hijau, bold, border grid, merge cells.
     */
    public function styles(Worksheet $sheet): void
    {
        $query = Product::query();
        if (is_null($this->brand) || trim($this->brand) === '') {
            $query->where(function($q) {
                $q->whereNull('brand')->orWhere('brand', '');
            });
        } else {
            $query->where('brand', $this->brand);
        }
        $productsCount = $query->count();

        $startDate = $this->startDate;
        $endDate   = $this->endDate;

        // Hitung jumlah hari (normalize ke startOfDay agar sama dengan while loop di array())
        $dayCount   = $startDate->copy()->startOfDay()->diffInDays($endDate->copy()->startOfDay()) + 1;
        $totalCols  = 2 + ($dayCount * 3) + 2; // NAMA + STOK AWAL + (ORD BNS DISC)*hari + SUB ORD + SUB BNS
        $lastColIdx = $totalCols;
        $lastColLet = $this->colLetter($lastColIdx);

        // --- Baris judul: merge & style ---
        $sheet->mergeCells("A1:{$lastColLet}1");
        $sheet->mergeCells("A2:{$lastColLet}2");
        $sheet->mergeCells("A3:{$lastColLet}3");

        $sheet->getStyle("A1:{$lastColLet}1")->applyFromArray([
            'font'      => ['bold' => true, 'size' => 14, 'color' => ['argb' => 'FF1E293B']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getStyle("A2:{$lastColLet}2")->applyFromArray([
            'font'      => ['bold' => false, 'size' => 10, 'color' => ['argb' => 'FF64748B']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // --- Header Row 4 (index baris Excel = 4): merge per tanggal ---
        // Kolom A: NAMA ITEM (merge baris 4 & 5)
        $sheet->mergeCells('A4:A5');
        // Kolom B: STOK AWAL (merge baris 4 & 5)
        $sheet->mergeCells('B4:B5');

        // Merge kolom tanggal (setiap 3 kolom)
        for ($i = 0; $i < $dayCount; $i++) {
            $startCol = 3 + ($i * 3);           // kolom ORD
            $endCol   = $startCol + 2;           // kolom DISC
            $startLet = $this->colLetter($startCol);
            $endLet   = $this->colLetter($endCol);
            $sheet->mergeCells("{$startLet}4:{$endLet}4");
        }

        // Merge SUB TOTAL kolom (baris 4 & 5)
        $subOrdCol = $this->colLetter($totalCols - 1);
        $subBnsCol = $this->colLetter($totalCols);
        $sheet->mergeCells("{$subOrdCol}4:{$subOrdCol}5");
        $sheet->mergeCells("{$subBnsCol}4:{$subBnsCol}5");

        // --- Style Header (baris 4 & 5): hijau ---
        $greenFill = [
            'fillType'  => Fill::FILL_SOLID,
            'startColor' => ['argb' => 'FF2D6A4F'],
        ];
        $headerStyle = [
            'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF'], 'size' => 9],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
                'wrapText'   => true,
            ],
            'fill'   => $greenFill,
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => ['argb' => 'FFFFFFFF'],
                ],
            ],
        ];
        $sheet->getStyle("A4:{$lastColLet}5")->applyFromArray($headerStyle);

        // --- Fix SUB TOTAL cells: set values explicitly & remove internal borders ---
        $sheet->setCellValue("{$subOrdCol}4", "SUB TOTAL\nORD");
        $sheet->setCellValue("{$subBnsCol}4", "SUB TOTAL\nBNS");

        $subTotalCleanStyle = [
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF'], 'size' => 9],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'fill' => $greenFill,
            'borders' => [
                'outline' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FFFFFFFF'],
                ],
                'inside' => [
                    'borderStyle' => Border::BORDER_NONE,
                ],
            ],
        ];
        $sheet->getStyle("{$subOrdCol}4:{$subOrdCol}5")->applyFromArray($subTotalCleanStyle);
        $sheet->getStyle("{$subBnsCol}4:{$subBnsCol}5")->applyFromArray($subTotalCleanStyle);

        // --- Style Data (baris 6 ke bawah) ---
        $lastDataRow = 5 + $productsCount;
        if ($lastDataRow >= 6) {
            $dataRange = "A6:{$lastColLet}{$lastDataRow}";
            $sheet->getStyle($dataRange)->applyFromArray([
                'font'      => ['size' => 9],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical'   => Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color'       => ['argb' => 'FFE2E8F0'],
                    ],
                ],
            ]);

            // Kolom NAMA ITEM: left-align
            $sheet->getStyle("A6:A{$lastDataRow}")->applyFromArray([
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                'font'      => ['bold' => true],
            ]);
        }

        // --- Row heights ---
        $sheet->getRowDimension(4)->setRowHeight(30);
        $sheet->getRowDimension(5)->setRowHeight(20);

        // Set kolom NAMA ITEM lebih lebar
        $sheet->getColumnDimension('A')->setWidth(28);

        // Set lebar kolom SUB TOTAL (auto-size tidak bekerja untuk merged cells)
        $sheet->getColumnDimension($subOrdCol)->setAutoSize(false)->setWidth(16);
        $sheet->getColumnDimension($subBnsCol)->setAutoSize(false)->setWidth(16);
    }

    /**
     * Konversi nomor kolom (1-based) ke huruf Excel (A, B, ..., Z, AA, AB, ...).
     */
    private function colLetter(int $n): string
    {
        $letter = '';
        while ($n > 0) {
            $n--;
            $letter = chr(65 + ($n % 26)) . $letter;
            $n      = intdiv($n, 26);
        }
        return $letter;
    }
}
