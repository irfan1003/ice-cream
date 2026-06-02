<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;


#[Fillable(['driver_id', 'order_id', 'spb_number', 'acc_kantor', 'acc_gudang', 'delivery_status'])]
#[Table(key: 'id_deliver', incrementing: true)]
class Delivery extends Model
{
    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id', 'id_user');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id_order');
    }
}
