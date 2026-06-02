<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['customer_id', 'sales_id', 'created_by', 'po_number', 'po_date', 'subtotal', 'tax_amount', 'discount_total', 'grand_total', 'status', 'rejected_note'])]
#[Table(key: 'id_po')]
class PurchaseOrders extends Model
{
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id_customer');
    }

    public function sales()
    {
        return $this->belongsTo(User::class, 'sales_id', 'id_user');
    }

    public function details()
    {
        return $this->hasMany(PurcaheOrderDetail::class, 'po_id', 'id_po');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id_user');
    }
}
