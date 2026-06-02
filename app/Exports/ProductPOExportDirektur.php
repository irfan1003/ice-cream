<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Models\Product;
use App\Exports\ProductBrandSheetDirektur;

class ProductPOExportDirektur implements WithMultipleSheets
{
    private $status;

    public function __construct($status = ['pending_director'])
    {
        $this->status = is_array($status) ? $status : [$status];
    }

    public function sheets(): array
    {
        $statuses = $this->status;
        $brands = Product::whereHas('poDetails', function ($query) use ($statuses) {
            $query->whereHas('purchaseOrder', function ($q) use ($statuses) {
                $q->whereIn('status', $statuses);
            });
        })
            ->select('brand')
            ->distinct()
            ->pluck('brand');

        $sheets = [];

        foreach ($brands as $brand) {
            $sheets[] = new ProductBrandSheetDirektur($brand, $statuses);
        }

        return $sheets;
    }
}
