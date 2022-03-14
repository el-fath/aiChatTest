<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    public function lockedCustomers()
    {
        return $this->belongsToMany(Customer::class, 'customer_vouchers')->wherePivot('type', 'locked')->withTimestamps();
    }

    public function redeemedCustomers()
    {
        return $this->belongsToMany(Customer::class, 'customer_vouchers')->wherePivot('type', 'redeemed')->withTimestamps();
    }
}
