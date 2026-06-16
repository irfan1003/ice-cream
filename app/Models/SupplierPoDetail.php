<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['po_supplier_id', 'product_id', 'qty', 'is_compatible', 'reject_reason'])]
#[Table('supplier_po_details', key: 'id_supplier_po_detail')]
class SupplierPoDetail extends Model
{
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id_product');
    }

    public function poSupplier()
    {
        return $this->belongsTo(SupplierPo::class, 'po_supplier_id', 'id_po_supplier');
    }
}
