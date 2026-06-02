<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['bonus_qty', 'discount', 'order_id', 'price_at_time', 'product_id', 'qty', 'total_item_price'])]
#[Table(key: 'id_order_detail')]
class OrderDetail extends Model
{
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id_order');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id_product');
    }
}
