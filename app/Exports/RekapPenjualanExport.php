<?php

namespace App\Exports;

use App\Models\OrderDetail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class RekapPenjualanExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithColumnFormatting
{
    protected $startDate;

    public function __construct($startDate)
    {
        $this->startDate = $startDate;
    }

    public function collection()
    {
        return OrderDetail::with(['order.customer', 'order.sales', 'order.delivery', 'product'])
            ->whereHas('order', function ($query) {
                $query->where('status', 'completed')
                    ->whereHas('delivery', function ($q) {
                        $q->where('delivery_status', 'delivered');
                    })
                    ->where('created_at', '>=', $this->startDate);
            })
            ->get();
    }

    public function headings(): array
    {
        return [
            'No. Pesanan',
            'No. SPB',
            'Tanggal Pesanan',
            'Pelanggan',
            'Sales',
            'Nama Produk',
            'Qty',
            'Satuan',
            'Harga (Rp)',
            'Diskon (Rp)',
            'Total Harga Item (Rp)'
        ];
    }

    public function map($detail): array
    {
        return [
            $detail->order->order_number,
            $detail->order->delivery->spb_number ?? '-',
            $detail->order->created_at->format('d/m/Y H:i'),
            $detail->order->customer->customer_name,
            $detail->order->sales->name,
            $detail->product->product_name,
            $detail->qty,
            $detail->product->unit ?? 'PCS',
            (float) $detail->price_at_time,
            (float) $detail->discount,
            (float) $detail->total_item_price
        ];
    }

    public function columnFormats(): array
    {
        return [
            'I' => '#,##0',
            'J' => '#,##0',
            'K' => '#,##0',
        ];
    }
}
