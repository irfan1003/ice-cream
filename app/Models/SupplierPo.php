<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['supplier_id', 'created_by', 'po_number', 'po_date', 'status'])]
#[Table('supplier_purchase_orders', key: 'id_po_supplier')]
class SupplierPo extends Model
{
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id_supplier');
    }

    public function details()
    {
        return $this->hasMany(SupplierPoDetail::class, 'po_supplier_id', 'id_po_supplier');
    }
}
