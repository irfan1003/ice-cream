<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['supplier_name', 'phone', 'email', 'address', 'pic_name'])]
#[Table('suppliers', key: 'id_supplier')]
class Supplier extends Model
{
    //
}
