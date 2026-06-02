<?php

namespace App\Exports;

use App\Models\Delivery;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DeliveryReportExport implements FromCollection, WithHeadings, WithMapping
{
    protected $period;

    public function __construct($period)
    {
        $this->period = $period;
    }

    public function collection()
    {
        $queryDelivery = Delivery::with(['order.customer', 'driver']);

        if ($this->period == 'today') {
            $queryDelivery->whereDate('created_at', Carbon::today());
        } elseif ($this->period == 'week') {
            $queryDelivery->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($this->period == 'month') {
            $queryDelivery->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year);
        }

        return $queryDelivery->orderBy('created_at', 'desc')->get();
    }

    public function map($delivery): array
    {
        return [
            $delivery->order->order_number ?? '-',
            Carbon::parse($delivery->delivery_date ?? $delivery->created_at)->format('d M Y'),
            $delivery->order->customer->customer_name ?? '-',
            $delivery->order->customer->address ?? '-',
            $delivery->delivery_status == 'shipped' ? 'Dalam Pengiriman' : ($delivery->delivery_status == 'delivered' ? 'Terkirim' : $delivery->delivery_status),
        ];
    }

    public function headings(): array
    {
        return [
            'No. Order',
            'Tanggal Kirim',
            'Pelanggan',
            'Alamat',
            'Status',
        ];
    }
}
