<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['zone_name'])]
#[Table(key: 'id_zone')]
class Zone extends Model
{
    public function customer()
    {
        return $this->hasOne(Customer::class, 'zone_id', 'id_zone');
    }
}
