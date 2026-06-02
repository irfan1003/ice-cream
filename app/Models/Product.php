<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['brand', 'product_name', 'current_stock', 'image', 'purchase_price', 'selling_price', 'unit'])]
#[Table(key: 'id_product')]
class Product extends Model
{
    public function orderDetail()
    {
        return $this->hasMany(OrderDetail::class, 'product_id', 'id_product');
    }

    public function log()
    {
        return $this->hasMany(StockLog::class, 'product_id', 'id_product');
    }

    public function poDetails()
    {
        return $this->hasMany(PurcaheOrderDetail::class, 'product_id', 'id_product');
    }
}
