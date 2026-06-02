<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['customer_id', 'created_by', 'discount_total', 'grand_total', 'order_date', 'order_number', 'sales_id', 'subtotal', 'tax_amount', 'status', 'rejected_note', 'invoice_pdf', 'po_id'])]
#[Table(key: 'id_order')]
class Order extends Model
{
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id_customer');
    }

    public function orderBySales()
    {
        return $this->belongsTo(User::class, 'created_by', 'id_user');
    }

    public function orderByPelanggan()
    {
        return $this->belongsTo(User::class, 'created_by', 'id_user');
    }

    public function sales()
    {
        return $this->belongsTo(User::class, 'sales_id', 'id_user');
    }

    public function orderDetail()
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'id_order');
    }

    public function delivery()
    {
        return $this->hasOne(Delivery::class, 'order_id', 'id_order');
    }
}
