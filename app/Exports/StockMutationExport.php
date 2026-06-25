<?php

namespace App\Exports;

use App\Models\Product;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class StockMutationExport implements WithMultipleSheets
{
    protected Carbon $startDate;
    protected Carbon $endDate;

    public function __construct(Carbon $startDate, Carbon $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate   = $endDate;
    }

    /**
     * Membuat sheet-sheet terpisah berdasarkan brand produk.
     */
    public function sheets(): array
    {
        // Ambil brand unik dari produk
        $brands = Product::select('brand')
            ->distinct()
            ->orderBy('brand')
            ->pluck('brand')
            ->toArray();

        $sheets = [];

        foreach ($brands as $brand) {
            $sheetName = (is_null($brand) || trim($brand) === '') ? 'Lainnya' : trim($brand);
            
            // Bersihkan karakter terlarang di nama sheet Excel: \ / ? * : [ ]
            $sheetName = str_replace(['\\', '/', '?', '*', ':', '[', ']'], '', $sheetName);
            
            // Limit maksimal 31 karakter untuk nama sheet Excel
            $sheetName = substr($sheetName, 0, 31);
            if (trim($sheetName) === '') {
                $sheetName = 'Lainnya';
            }

            $sheets[] = new StockMutationSheetExport($this->startDate, $this->endDate, $brand, $sheetName);
        }

        // Jika tidak ada brand / produk sama sekali, buat 1 sheet default
        if (empty($sheets)) {
            $sheets[] = new StockMutationSheetExport($this->startDate, $this->endDate, null, 'Mutasi Stok');
        }

        return $sheets;
    }
}
