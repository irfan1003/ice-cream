<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;


#[Fillable(['user_id', 'zone_id', 'customer_name', 'address', 'phone'])]
#[Table(key: 'id_customer')]
class Customer extends Model
{
    public function zone()
    {
        return $this->belongsTo(Zone::class, 'zone_id', 'id_zone');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id', 'id_customer');
    }

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrders::class, 'customer_id', 'id_customer');
    }
}
