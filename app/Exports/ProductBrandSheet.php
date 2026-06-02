<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Models\Product;

class ProductBrandSheet implements FromCollection, WithTitle, WithHeadings, WithMapping
{
    private $brand;
    private $status;
    private $rowNumber = 0;

    public function __construct($brand, $status = ['pending_admin'])
    {
        $this->brand = $brand;
        $this->status = is_array($status) ? $status : [$status];
    }

    public function collection()
    {
        $statuses = $this->status;

        return Product::where('brand', $this->brand)
            ->whereHas('poDetails', function ($query) use ($statuses) {
                $query->whereHas('purchaseOrder', function ($q) use ($statuses) {
                    $q->whereIn('status', $statuses);
                });
            })
            ->withSum([
                'poDetails as total_po_qty' => function ($query) use ($statuses) {
                    $query->whereHas('purchaseOrder', function ($q) use ($statuses) {
                        $q->whereIn('status', $statuses);
                    });
                }
            ], 'qty')
            ->get();
    }

    public function title(): string
    {
        return $this->brand ?: 'Tanpa Brand';
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Item',
            'Jumlah PO'
        ];
    }

    public function map($product): array
    {
        $this->rowNumber++;
        return [
            $this->rowNumber,
            $product->product_name,
            $product->total_po_qty ?? 0
        ];
    }
}
