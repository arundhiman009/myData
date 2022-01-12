<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashoutRequest extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function to_user()
    {
        return $this->belongsTo(User::class,'to_id');
    }

    public function location()
    {
        return $this->belongsTo(CashoutLocation::class, 'offline_location_id');
    }
}
