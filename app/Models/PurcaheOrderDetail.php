<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['po_id', 'product_id', 'qty', 'bonus_qty', 'discount', 'price_at_time', 'total_item_price'])]
#[Table(key: 'id_po_detail')]
class PurcaheOrderDetail extends Model
{
    protected $table = 'purchase_order_details';

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id_product');
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrders::class, 'po_id', 'id_po');
    }
}
