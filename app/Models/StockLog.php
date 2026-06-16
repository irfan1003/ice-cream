<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['product_id', 'user_id',  'quantity', 'reference_note', 'type',  'po_supplier_id', 'order_id'])]
#[Table(key: 'id_log')]
class StockLog extends Model
{
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id_product');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }
}
