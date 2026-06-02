<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'signature'])]
#[Hidden(['password', 'remember_token'])]
#[Table(key: 'id_user')]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function deliver()
    {
        return $this->hasMany(Delivery::class, 'driver_id', 'id_user');
    }

    public function customer()
    {
        return $this->hasOne(Customer::class, 'user_id', 'id_user');
    }

    public function customers()
    {
        return $this->hasManyThrough(Customer::class, Order::class, 'sales_id', 'id_customer', 'id_user', 'customer_id');
    }
}
