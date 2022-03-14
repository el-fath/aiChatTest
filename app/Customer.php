<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $guarded = [];
    protected $appends = ['new_locked_voucher', 'new_redeemed_voucher'];

    public function lockedVouchers()
    {
        return $this->belongsToMany(Voucher::class, 'customer_vouchers')->wherePivot('type', 'locked')->withTimestamps();
    }

    public function redeemedVouchers()
    {
        return $this->belongsToMany(Voucher::class, 'customer_vouchers')->wherePivot('type', 'redeemed')->withTimestamps();
    }

    public function getNewLockedVoucherAttribute()
    {
        return $this->lockedVouchers->last();
    }

    public function getNewRedeemedVoucherAttribute()
    {
        return $this->redeemedVouchers->last();
    }

    public function transactions()
    {
        return $this->hasMany(PurchaseTransaction::class);
    }
}
