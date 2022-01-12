<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentInfo extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class,'transaction_id','id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function to_user()
    {
        return $this->belongsTo(User::class,'to_id');
    }

    public function paymentInfo()
    {
        return $this->hasOne(PaymentInfo::class,'transaction_id');
    }

    public function referralInfo()
    {
        return $this->belongsTo(User::class,'referrer_id');
    }
}
