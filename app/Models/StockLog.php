<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['product_id', 'user_id', 'verification_status', 'quantity', 'reference', 'type', 'warehouse_note', 'final_status'])]
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
