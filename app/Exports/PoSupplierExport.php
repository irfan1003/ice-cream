<?php

namespace App\Exports;

use App\Models\SupplierPo;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class PoSupplierExport implements WithMultipleSheets
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function sheets(): array
    {
        $sheets = [];
        $po = SupplierPo::with(['supplier', 'details.product'])->findOrFail($this->id);

        $groupedDetails = $po->details->groupBy(function ($detail) {
            return $detail->product->brand ?? 'Tanpa Brand';
        });

        foreach ($groupedDetails as $brandName => $details) {
            $sheets[] = new PoSupplierBrandSheet($brandName, $details);
        }

        return $sheets;
    }
}

class PoSupplierBrandSheet implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    protected $brandName;
    protected $details;
    protected $rowNumber = 0;

    public function __construct($brandName, $details)
    {
        $this->brandName = $brandName;
        $this->details = $details;
    }

    public function collection()
    {
        return new Collection($this->details);
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Item/Nama Product',
            'Qty'
        ];
    }

    public function map($detail): array
    {
        $this->rowNumber++;

        return [
            $this->rowNumber,
            $detail->product->product_name ?? 'Produk Tidak Ditemukan',
            $detail->qty
        ];
    }

    public function title(): string
    {
        return substr($this->brandName, 0, 31);
    }
}
